let rowId, projectRowId;
function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}



$(document).on('submit','#add-project-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/dhg-projects',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.dhg-project-form-btn').attr('disabled',true).val('Saving...');
        },success: function(result){
            console.log(result);
            if(result.success === true)
            {
                $('#add-project-form').trigger('reset');
                $('.textarea').summernote('reset');
                $('#add-project-form .select2').val('').trigger('change');
                $('.text-danger').remove();
                toastr.success(result.message);

                $('#project-list').DataTable().ajax.reload();

                $('#add-new-project-modal').modal('toggle');
            }
            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.dhg-project-form-btn').attr('disabled',false).val('Save');
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
            $('.dhg-project-form-btn').attr('disabled',false).val('Save');
        }
    });
    clear_errors('client','builder','address','lot_price','house_price','lot_area','floor_area','date_created');
});

// $(document).on('click','.edit-btn', function(){
//     rowId = this.id;
//     $('input[name=payment_id]').val(rowId);
// });

// $(document).on('submit','#check-admin-credential-form',function (form) {
//     form.preventDefault();
//
//     let data = $(this).serializeArray();
//     $.ajax({
//         'url' : '/admin/credential',
//         'type' : 'POST',
//         'data' : data,
//         beforeSend: function(){
//             $('.check-admin-credential-form-btn').attr('disabled',true).val('Sending...');
//         },
//         success: function (result) {
//             if(result.success === true)
//             {
//                 callModal();
//             }else if(result.success === false){
//                 toastr.warning(result.message);
//             }
//
//             $.each(result, function (key, value) {
//                 let element = $('.'+key);
//
//                 element.find('.error-'+key).remove();
//                 element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
//             });
//             $('.check-admin-credential-form-btn').attr('disabled',false).val('Send');
//         },error: function(xhr,status,error){
//
//             if(xhr.responseJSON.message === "CSRF token mismatch.")
//             {
//                 toastr.info('Session expired. Reloading Page Now...');
//                 setTimeout(function(){
//                     location.reload();
//                 }, 3500);
//             }
//         }
//     });
//     clear_errors('password');
// });
//
// function callModal()
// {
//     $.ajax({
//         'url' : '/client-payment/edit/layout/'+rowId,
//         'type' : 'GET',
//         beforeSend: function(){
//           $('.edit-client-modal-container').remove();
//         },
//         success: function (modal) {
//             $(modal).insertAfter('.wrapper');
//             $('#check-admin-credential-modal').modal('toggle');
//             $('#edit-client-payment-modal').modal();
//             $('#check-admin-credential-form').trigger('reset');
//
//             $('#edit-client-payment-form .modal-content').prepend('<input type="hidden" name="payment_id" value="'+rowId+'">');
//         }
//     });
// }

$(document).on('submit','#edit-client-payment-form', function(form){
    form.preventDefault();

    let data = $(this).serializeArray();
    $.ajax({
        'url' : '/client-payment/'+rowId,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function () {
            $('.dhg-btn').attr('disabled',true).val('Saving...');
        },success: function (result) {

            if(result.success === true)
            {
                $('#edit-client-payment-modal').modal('toggle');
                toastr.success(result.message);
                $('#payment-list').DataTable().ajax.reload();
            }else if(result.success === false){
                toastr.warning(result.message);
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
            $('.dhg-btn').attr('disabled',false).val('Save');
        }
    });
});





$(document).on('click','.delete-payment-btn', function () {
    rowId = this.id;

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
                'url' : '/client-payment/'+rowId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE'},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        $('#payment-list').DataTable().ajax.reload();

                        Swal.fire(
                            'Deleted!',
                            output.message,
                            'success'
                        );
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });

        }
    });
});

