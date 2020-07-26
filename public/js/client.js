$(document).on('submit','#client-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url'   : '/clients',
        'type'  : 'POST',
        'data'  : data,
        beforeSend: function () {
            $('.submit-contact-btn').val('Saving ... ').attr('disabled',true);
        },success: function (result) {
            console.log(result);
            if(result.success === true)
            {
                toastr.success(result.message);
                $('#client-form').trigger('reset');
                $('#add-client-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
            $('.submit-contact-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });

    clear_errors('firstname','lastname','address','username','password');
});
