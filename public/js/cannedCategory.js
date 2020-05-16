$(document).on('submit','.category-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/canned-category',
        'type' : 'POST',
        'data' : data,
        beforeSend: function () {
            $('.category-btn').val('Saving ... ').attr('disabled',true);
        },success: function (result) {
            console.log(result);

            if(result.success === true)
            {
                $('.category-form').trigger('reset');
                $('.canned-category').append('<option value="'+result.catValue.id+'">'+result.catValue.name+'</option>');
                toastr.success(result.message);

                var table = $('#canned-category-list').DataTable();
                table.ajax.reload();
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.category-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('category_name');
});
