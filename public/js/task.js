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

            if(result.success === true)
            {
                let table = $('#task-list').DataTable();
                table.ajax.reload();
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
    clear_errors('title','description','due_date','priority');
});

$(document).on('click','.edit-task-btn',function(){
    let id = this.id;

    $.ajax({
        'url' : '/tasks/'+id,
        'type' : 'GET',
        beforeSend: function(){
            $('#edit-task-form input, #edit-task-form select, #edit-task-form textarea').attr('disabled',true);
        },success: function(result){
            $('#taskId').val(id);
            $('#edit_title').val(result.name);
            $('#edit_description').val(result.description);
            $('#edit_priority').val(result.priority_id);
            $('#edit_collaborator').val(result.collaborator).change();

            $('#edit-task-form input, #edit-task-form select, #edit-task-form textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('submit','#edit-task-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();
    $.ajax({
        'url' : '/tasks/'+data[2].value,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('#edit-task-form input, #edit-task-form select, #edit-task-form textarea').attr('disabled',true);
        },success: function(result){

            if(result.success === true)
            {
                let table = $('#task-list').DataTable();
                table.ajax.reload();
                $('#edit-task-modal').modal('toggle');
            }

            $('#edit-task-form input, #edit-task-form select, #edit-task-form textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

