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
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },
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
            }else{
                $('.submit-form-btn').attr('disabled',false);
                $('.spinner').hide();
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

$(document).ready(function(){

    /*add permission*/
    $('#permission-form').submit(function(form){
        form.preventDefault();
        let data = $('#permission-form').serialize();

        submitform(
            '/permissions',
            'POST',
            data,
            'New Permission Successfully Added!',
            true,
            '',
            false,
        );
        clear_errors('permission');
    });

    /*edit permission*/
    $('#edit-permission-form').submit(function(form){
        form.preventDefault();
        let data = $('#edit-permission-form').serialize();
        let id = $('#updatePermissionId').val();

        submitform(
            '/permissions/'+id,
            'PUT',
            data,
            'New Permission Successfully Updated!',
            true,
            '',
            false,
        );
        clear_errors('edit_permission');
    });

    /*delete permission*/
    $('#delete-permission-form').submit(function(form){
        form.preventDefault();
        let id = $('#deletePermissionId').val();
        let data = $('#delete-permission-form').serialize();

        //console.log(data);
        submitform(
            '/permissions/'+id,
            'DELETE',
            data,
            'Permission Successfully Removed!',
            false,
            '',
            true
        );
    });
});

/*edit trigger popup*/
$(document).on('click','.edit-permission-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    $('#edit_permission').val(data[0]);
    $('#updatePermissionId').val(id);

    $.ajax({
        'url' : '/permission-roles',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'type' : 'POST',
        'data' : {'name':data[0]},
        success: function(result){
            $('#edit_roles').val(result).change();
        }
    });
});

/*delete trigger popup*/
$(document).on('click','.delete-permission-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    $('.delete-permission-name').html('<strong style="color:yellow;">'+data[0]+'</strong>');
    $('#deletePermissionId').val(id);
});
