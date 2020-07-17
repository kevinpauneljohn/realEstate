$(document).on('submit','#task-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/child-tasks',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.submit-task-btn').val('Saving ...').attr('disabled',true);
        },success: function(result){
            if(result.success === true)
            {
                $('#task-form').trigger('reset');
                $('#task-form #collaborator').empty();
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
    clear_errors('title','description','priority','assignee');
});


$(document).on('click','.read-more',function(){
    let id = this.id;
    // console.log(id);

    $.ajax({
        'url' : '/child-tasks/'+id,
        'type' : 'GET',
        beforeSend: function(){

        },success: function(result){
            console.log(result);

            $('#read-more-modal .modal-title').text(result.title);
            $('#read-more-modal .modal-body .description').text(result.description);
            $('#read-more-modal .user-name').val(result.assignee_id).change();
            $('#read-more-modal .created-by').text(result.user_id);
            $('#read-more-modal .date-created').text(result.created_at);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});
