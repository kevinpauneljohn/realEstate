$(document).on('submit','.add-canned-message',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/canned',
        'type' : 'POST',
        'data' : data,
        beforeSend: function () {
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },success: function (result) {
            console.log(result);

            if(result.success === true)
            {
                toastr.success(result.message);
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.submit-form-btn').attr('disabled',false);
            $('.spinner').hide();
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('title','body');
});
