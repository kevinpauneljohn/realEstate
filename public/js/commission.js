function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

function submitform(url , type , data , message , reload = true, elementAttr, consoleLog = true)
{
    $.ajax({
        'url' : url,
        'type' : type,
        'data' : data,
        'cache' : false,
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },
        success: function(result, status, xhr){
            if(consoleLog === true)
            {
                console.log(result);
            }
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success(message)
                    setTimeout(function(){
                        if(reload === true)
                        {
                            location.reload();
                        }
                    },1500);
                });
            }else{
                $('.submit-form-btn').attr('disabled',false);
                $('.spinner').hide();
            }

            $.each(result, function (key, value) {
                var element = $(elementAttr+'#'+key);

                element.closest(elementAttr+'div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
}

$(document).ready(function () {

    // $('#add-commission-form').submit(function (form) {
    //     form.preventDefault();
    //
    //     let data = $('#add-commission-form').serialize();
    //     submitform(
    //         '/commissions',
    //         'POST',
    //         data,
    //         'Commission Successfully Added!',
    //         true,
    //         '',
    //         false,
    //     );
    // });
});

$(document).on('submit','#add-commission-form',function (form) {
    form.preventDefault();

    let data = $(this).serializeArray();
    $.ajax({
        'url' : '/commissions',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.submit-commission-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            if(result.success === true)
            {
                let table = $('#commission-list').DataTable();
                table.ajax.reload();

                toastr.success(result.message);
                $('#add-commission-form').trigger('reset');
                $('#add-commission-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                var element = $('#'+key);

                element.closest('div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

            $('.submit-commission-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('click','.add-commission-btn',function(){
    $('select[name=commission_rate]').val('');
    $('select[name=project]').val('');
    $('.commission_remark').addClass("hidden");
});

let commissionModal = $('#add-commission-modal');
$('#add-commission-modal').on('hidden.bs.modal', function () {
    commissionModal.find('.modal-title').text("Add Commision");
    commissionModal.find('form').attr('id','add-commission-form');
    commissionModal.find('.commission_remark').addClass("hidden");
    commissionModal.find('input[name=commission_id').remove();
    commissionModal.find('.commission_text_remark').val('');
    commissionModal.find('select[name=commission_rate]').prop('disabled', false);
    commissionModal.find('select[name=project]').prop('disabled', false);
    commissionModal.find('.submit-commission-btn').val('Save');
    $('.submit-commission-btn').prop('disabled', false);
});

let rate;
$(document).on('click','.edit-commission-btn',function(){
    let id = this.id;
    let is_admin = $('.commission_text_is_admin').val();
    if (is_admin == 1) {
        $('.submit-commission-btn').prop('disabled', false);
    } else {
        $('.submit-commission-btn').prop('disabled', true);
    }

    commissionModal.find('.modal-title').text("Edit Commision");
    commissionModal.find('form').attr('id','edit-commission-form').prepend('<input type="hidden" name="commission_id" value="'+id+'">');
    
    commissionModal.find('.commission_remark').removeClass("hidden");
    commissionModal.find('.commission_remark label').html("<span class='required'>*</span>Reason to Update:");
    commissionModal.find(".commission_text_remark").prop('disabled', true);
    $('#add-commission-modal').modal('toggle');
    $.ajax({
        'url' : '/commissions/'+id+'/show',
        'type' : 'GET',
        beforeSend: function(){

        },success: function(result){
            rate = result.rate;
            if ( $("#commission_rate option[value='"+result.rate+"']").length == 0 ){
                $('#commission_rate').append('<option value="'+result.rate+'" selected>'+result.rate+'%</option>');
            } else {
                commissionModal.find('select[name=commission_rate]').val(result.rate);
            }
            
            commissionModal.find('select[name=project]').val(result.project_id);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('submit','#edit-commission-form',function (form) {
    form.preventDefault();

    let data = $(this).serializeArray();
    $.ajax({
        'url' : '/commissions/'+data[0].value,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('.submit-commission-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            if(result.success === true)
            {
                let table = $('#commission-list').DataTable();
                table.ajax.reload();

                toastr.success(result.message);
                $('#edit-commission-form').trigger('reset');
                $('#add-commission-modal').modal('toggle');

                $('.submit-commission-btn').val('Save').attr('disabled',false);

                $('.commission_remark').addClass("hidden");
            }
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('click','.delete-commission-btn',function(){
    let id = this.id;

    $('.submit-commission-btn').prop('disabled', true);
    commissionModal.find('.modal-title').text("Delete Commision");
    commissionModal.find('form').attr('id','delete-commission-form').prepend('<input type="hidden" name="commission_id" value="'+id+'">');
    
    commissionModal.find('.commission_remark').removeClass("hidden");
    commissionModal.find('.commission_remark label').html("<span class='required'>*</span>Reason to Delete:");
    commissionModal.find(".commission_text_remark").prop('disabled', false);
    commissionModal.find('select[name=commission_rate]').prop('disabled', true);
    commissionModal.find('select[name=project]').prop('disabled', true);
    commissionModal.find('.submit-commission-btn').val('Delete');
    commissionModal.find('.reset').addClass('hidden');
    $('#add-commission-modal').modal('toggle');
    $.ajax({
        'url' : '/commissions/'+id+'/show',
        'type' : 'GET',
        beforeSend: function(){

        },success: function(result){
            commissionModal.find('select[name=commission_rate]').val(result.rate);
            commissionModal.find('select[name=project]').val(result.project_id);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('submit','#delete-commission-form',function (form) {
    form.preventDefault();
    let data = $(this).serializeArray();
    $.ajax({
        'url' : '/user/commission/delete',
        'type' : 'POST',
        'data' : data,
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function(){
            $('.submit-commission-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            if(result.success === true)
            {
                let table = $('#commission-list').DataTable();
                table.ajax.reload();

                toastr.success(result.message);
                $('#edit-commission-form').trigger('reset');
                $('#add-commission-modal').modal('toggle');

                $('.submit-commission-btn').val('Save').attr('disabled',false);

                $('.commission_remark').addClass("hidden");
            }
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('change','#project',function(){
    let value = this.value;
    $('.commission_text_remark').prop('disabled', false);
    if(value != "")
    {
        $.ajax({
            'url' : '/upline-commission/'+value,
            'type' : 'GET',
            beforeSend: function(){
                $('#commission_rate').html("");
                $('#commission_rate').prop('disabled', true);
            },
            success: function (result) {
                setTimeout(function() { 
                    $('#commission_rate').prop('disabled', false);
                }, 300);

                $('#commission_rate').append('<option value=""> -- Select -- </option>');
                $.each(result, function (key, value) {
                    if (value == rate) {
                        $('#commission_rate').append('<option value="'+value+'" selected>'+value+'%</option>');
                    } else {
                        $('#commission_rate').append('<option value="'+value+'">'+value+'%</option>');
                    }
                    
                });
            }
        });
    }
});

$(document).on('change','#commission_rate',function(){
    let value = this.value;
    
    if(value != "")
    {
        $(".commission_text_remark").prop('disabled', false);
    } else {
        $(".commission_text_remark").prop('disabled', true);
    }
});

$(".commission_text_remark").on('change keyup paste', function() {
    if($(this).val() != '') {
        $('.submit-commission-btn').prop('disabled', false);
    } else {
        $('.submit-commission-btn').prop('disabled', true);
    }
});

$(document).on('click','.delete-commission-btn-admin',function () {
    let id = this.id;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Move to trash!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                'url' : '/user/commission/delete',
                'type' : 'POST',
                'data' : {'id': id},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },
                success: function(output){
                    if(output.success === true){
                        toastr.success(output.message);
                        let table = $('#commission-list').DataTable();
                        table.ajax.reload();

                    }else if(output.success === false){
                        toastr.warning(output.message);
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });

        }
    });
});
