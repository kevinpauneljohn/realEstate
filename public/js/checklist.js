$(document).on('submit','#checklist-form',function (form) {
    form.preventDefault();

    let data = $(this).serializeArray();
    //console.log(data);

    $.ajax({
        'url'  : '/check-list',
        'type' : 'POST',
        'data' : data,
        beforeSend: function () {
            $('.submit-checklist-btn').val('Saving ... ').attr('disabled',true);
        },success: function (result) {
            console.log(result);
            if(result.success === true)
            {
                toastr.success(result.message);
                $('#checklist-form').trigger('reset');
                $('#add-checklist-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
             $('.submit-checklist-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });

    clear_errors('title','description');
});
