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

$(document).on('submit','#add-requirements-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/requirements',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('#add-requirements-form input,#add-requirements-form select,#add-requirements-form .row-description-btn').attr('disabled',true);
            $('.submit-requirements-btn').val('Saving ... ');
        },success: function(result){
            if(result.success === true)
            {
                $('#add-requirements-form').trigger('reset');
                let table = $('#requirements-list').DataTable();
                table.ajax.reload();
                toastr.success(result.message);
                $('#add-new-requirements-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                let element = $('#'+key);

                element.closest('div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

            $('#add-requirements-form input,#add-requirements-form select,#add-requirements-form .row-description-btn').attr('disabled',false);
            $('.submit-requirements-btn').val('Save');
        },error: function(xhr,status,error){
            console.log(xhr);
        }
    });
    clear_errors('title','financing_type');
});

$(document).on('submit','#edit-requirement-form',function(form){
    form.preventDefault();
    let data = $('#edit-requirement-form').serializeArray(),
        id = $('#updateRequirementId').val();

    $.ajax({
        'url' : '/requirements/'+id,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('#edit-requirement-form input,#edit-requirement-form select,#edit-requirement-form .edit-row-description-btn').attr('disabled',true);
            $('.edit-form-btn').val('Saving ... ');
        },success: function(result){

            if(result.success === true)
            {
                let table = $('#requirements-list').DataTable();
                table.ajax.reload();
                toastr.success(result.message);
                $('#edit-requirement-modal').modal('toggle');
            }

            $('#edit-requirement-form input,#edit-requirement-form select,#edit-requirement-form .edit-row-description-btn').attr('disabled',false);
            $('.edit-form-btn').val('Save');
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('edit_title','edit_financing_type');
});

$(document).on('click','.delete-requirements-btn',function(){
    let id = this.id;
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                'url' : '/requirements/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        Swal.fire(
                            'Deleted!',
                            output.message,
                            'success'
                        );

                        let table = $('#requirements-list').DataTable();
                        table.ajax.reload();
                    }else{
                        toastr.error(output.message);
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });

        }
    });
});
//
// $(document).ready(function(){
//
//     $('#delete-requirements-form').submit(function(form){
//         form.preventDefault();
//
//         let data = $('#edit-requirement-form').serialize();
//         let id = $('#deleteRequirementsId').val();
//
//         submitform(
//             '/requirements/'+id,
//             'DELETE',
//             data,
//             'Requirements Successfully Deleted!',
//             false,
//             '',
//             true,
//         );
//     });
// });

$(document).on('click','.row-description-btn',function(){
    let value = this.value;

    let row = $('.row-description');

    if(value == 'plus')
    {
        $('.desc-inputs').append('<div class="row row-description">\n' +
            '                                    <div class="col-sm-9">\n' +
            '                                        <input type="text" name="description[]" class="form-control description"/>\n' +
            '                                    </div>\n' +
            '                                    <div class="col-sm-3">\n' +
            '                                        <button type="button" class="btn btn-success row-description-btn" value="plus"><i class="fa fa-plus"></i></button>\n' +
            '                                        <button type="button" class="btn btn-danger row-description-btn" value="minus"><i class="fa fa-minus"></i></button>\n' +
            '                                    </div>\n' +
            '                                </div>');
    }else{
        this.closest('.row-description').remove();
    }
});

$(document).on('click','.edit-row-description-btn',function(){
    let value = this.value;
    console.log(this.id);

    if(value == 'plus')
    {
        $('.edit-desc-inputs').append('<div class="row edit-row-description">\n' +
            '                                    <div class="col-sm-9">\n' +
            '                                        <input type="text" name="edit_description[]" class="form-control edit_description"/>\n' +
            '                                    </div>\n' +
            '                                    <div class="col-sm-3">\n' +
            '                                        <button type="button" class="btn btn-success edit-row-description-btn" value="plus"><i class="fa fa-plus"></i></button>\n' +
            '                                        <button type="button" class="btn btn-danger edit-row-description-btn" value="minus"><i class="fa fa-minus"></i></button>\n' +
            '                                    </div>\n' +
            '                                </div>');
    }else{
        if(this.id == "")
        {
            this.closest('.edit-row-description').remove();
        }else{
            $('#edit-requirement-form').find('#input_'+this.id+', #plus_'+this.id+', #'+this.id).attr('disabled',true);
            $('#edit-requirement-form .modal-body').append('<input type="hidden" name="delete_requirements[]" value="'+this.id+'">');
            $('#update_description_'+this.id).remove()
        }
    }
});

$(document).on('click','.edit-btn',function(){
    let id = this.id;


    $.ajax({
        'url' : '/get-requirements',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'id':id},
        beforeSend: function(){
            $('.edit-row-description').remove();
        },
        success: function(result){
            $('#updateRequirementId').val(id);
            $('#edit_title').val(result.template.name);
            $('#edit_financing_type').val(result.template.type).change();

            $.each(result.requirements, function (key, value) {
                $('.edit-desc-inputs').append('<div class="row edit-row-description">\n' +
                    '                                    <div class="col-sm-9">\n' +
                    '<input type="text" name="edit_description['+value.id+']" class="form-control edit_description" id="input_'+value.id+'" value="'+value.description+'"/>\n' +
                    '                                    </div>\n' +
                    '                                    <div class="col-sm-3">\n' +
                    '                                        <button type="button" class="btn btn-success edit-row-description-btn" id="plus_'+value.id+'" value="plus"><i class="fa fa-plus"></i></button>\n' +
                    '                                        <button type="button" class="btn btn-danger edit-row-description-btn" id="'+value.id+'" value="minus"><i class="fa fa-minus"></i></button>\n' +
                    '                                    </div>\n' +
                    '                                </div>');
            });
        }
    });
});

$(document).on('click','.delete-requirements-btn',function(){
    let id = this.id;

    $('#deleteRequirementsId').val(id);
    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    $('.delete-requirements-name').html('<h3 style="color:yellow;">'+data[0]+'</h3>')
});
