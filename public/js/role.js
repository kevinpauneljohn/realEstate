function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

function submitform(url , type , data , message , reload = true, elementAttr, consoleLog = true)
{
    $.ajax({
        'url' : url,
        'type' : type,
        'data' : data,
        'cache' : false,
        success: function(result, status, xhr){
            if(consoleLog === true)
            {
                console.log(result);
            }
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success(message)
                    setTimeout(function(){
                        if(reload === true)
                        {
                            location.reload();
                        }
                    },1500);
                });
            }
            $.each(result, function (key, value) {
                var element = $(elementAttr+'#'+key);

                element.closest(elementAttr+'div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
}


/*add roles*/
$(document).on('submit','#role-form', function(form){
    form.preventDefault();

    let data = $('#role-form').serialize();
    submitform(
        '/roles',
        'POST',
        data,
        'New Role Successfully Added!',
        true,
        '',
        false
    );
    clear_errors('role');
});

/*edit role*/
$(document).on('submit','#edit-role-form', function(form){
    form.preventDefault();
    let id = $('#updateRoleId').val();
    let data = $('#edit-role-form').serialize();
    submitform(
        '/roles/'+id,
        'PUT',
        data,
        'Role Successfully Edited!',
        true,
        '',
        false
    );
    clear_errors('edit_role');
});

/*Delete Role*/
$(document).on('submit','#delete-role-form', function(form){
    form.preventDefault();
    let id = $('#deleteRoleId').val();
    let data = $('#delete-role-form').serialize();
    submitform(
        '/roles/'+id,
        'DELETE',
        data,
        'Role Successfully Removed!',
        true,
        '',
        false
    );
});

$(document).on('click','.edit-role-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    $('#updateRoleId').val(id);
    $('#edit_role').val(data[0]);
});

$(document).on('click','.delete-role-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    $('.delete-role-name').text(data[0]);
    $('#deleteRoleId').val(id);
});
