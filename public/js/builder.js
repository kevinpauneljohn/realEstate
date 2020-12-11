function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

$(document).on('submit','#add-builder-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/builders',
        'type': 'POST',
        'data': data,
        beforeSend: function(){
            $('.builder-form-btn').attr('disabled',true).val('Saving...');
        },success: function(result){

            if(result.success === true)
            {
                $('#add-builder-form').trigger('reset');
                $('.textarea').summernote('reset');
                toastr.success(result.message);
                $('#add-new-builder-modal').modal('toggle');
            }
            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.builder-form-btn').attr('disabled',false).val('Save');
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
        }
    });

    clear_errors('name');
});
