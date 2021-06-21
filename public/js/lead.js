function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

/*delete trigger popup*/
$(document).on('click','.delete-lead-btn',function () {
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
                'url' : '/leads/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        Swal.fire(
                            'Trashed!',
                            output.message,
                            'success'
                        );

                        let table = $('#leads-list').DataTable();
                        table.ajax.reload(null, false);
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


$(document).on('click','.view-details',function(){
    id = this.id;
    $.ajax({
        'url' : '/leads/get',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'id':id},
        beforeSend: function () {
            $('.image-loader').show();
            $('.lead-details-table').remove();
        },success: function(result){
            let mobile = result.mobileNo != null? result.mobileNo:"",
                landline = result.landline != null? result.landline:"",
                email = result.email != null? result.email:"",
                status = result.status != null? result.status:"",
                income_range = result.income_range != null? result.income_range:"";

            $('#lead-details .modal-body').append('<table class="table table-bordered table-hover lead-details-table">' +
                '<tr><td>Status</td><td>'+result.lead_status+'</td></tr>' +
                '<tr><td>Date Inquired</td><td>'+dateToYMD(new Date(result.date_inquired))+'</td></tr>' +
                '<tr><td>Full Name</td><td>'+result.id+'</td></tr>' +
                '<tr><td>Mobile Phone</td><td>'+mobile+'</td></tr>' +
                '<tr><td>Land line</td><td>'+landline+'</td></tr>' +
                '<tr><td>Email</td><td>'+email+'</td></tr>' +
                '<tr><td>Civil Status</td><td>'+status+'</td></tr>' +
                '<tr><td>Income Range</td><td>'+income_range+'</td></tr>' +
                '<tr><td>Project Interested</td><td>'+result.project+'</td></tr>' +
                '<tr><td colspan="2"><strong>Remarks</strong><p>'+result.remarks+'</p></td></tr></table>');

            $('.image-loader').hide();
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
        }
    });
});
///
function dateToYMD(date) {
    var d = date.getDate();
    var m = date.getMonth() + 1; //Month from 0 to 11
    var y = date.getFullYear();
    return '' + y + '-' + (m<=9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
}

$(document).on('click','.set-status', function(){
    let id = this.id;

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    $('.change-status').val(data[6]).change();
    $('#lead_id').val(id);
});

$(document).on('submit','#change-status-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/leads/status/update',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },
        success: function (result) {

            if(result.success === true)
            {
                $('#change-status-form').trigger('reset');
                let table = $('#leads-list').DataTable();
                table.ajax.reload(null, false);
                toastr.success(result.message);
                $('#set-status').modal('toggle');

            }else if(result.success === false){
                toastr.error(result.message);
            }

            $('.submit-form-btn').attr('disabled',false);
            $('.spinner').hide();

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

        },error: function(xhr,status,error){
            console.log(xhr, status, error);
        }
    });

    clear_errors('status','notes');
    ///
});


