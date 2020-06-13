$(document).on('submit','#task-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/tasks',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.submit-task-btn').val('Saving ...').attr('disabled',true);
        },success: function(result){
            console.log(result);

            if(result.success === true)
            {
                let table = $('#contest-list').DataTable();
                table.ajax.reload();
                $('#task-form').trigger('reset');
                $('#task-form #collaborator').val().change();
                toastr.success(result.message);

                $('#add-task-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
            $('.submit-task-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('title','description','priority','collaborator');
});
