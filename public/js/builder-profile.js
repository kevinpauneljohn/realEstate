function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}
$(document).on('submit','#builder-member-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/add-member/builder',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.member-btn').attr('disabled',true).val('Adding...');
        },
        success: function(result){

            if(result.success === true)
            {
                toastr.success(result.message);
                $('#builder-member-form select').val('').change();
                $('#member-list').DataTable().ajax.reload();
                $('#builder-member-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
            $('.member-btn').attr('disabled',false).val('Add');
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
            $('.member-btn').attr('disabled',false).val('Add');
        }
    });
    clear_errors('members');
});
