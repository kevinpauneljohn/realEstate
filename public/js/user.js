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

    /*add user*/
    // $('#user-form').submit(function(form){
    //     form.preventDefault();
    //
    //     let data = $('#user-form').serialize();
    //
    //     submitform(
    //         '/users',
    //         'POST',
    //         data,
    //         'New User Successfully Added!',
    //         false,
    //         '',
    //         true,
    //     );
    //     clear_errors('firstname','lastname','username','password','role');
    // });

    /*edit user*/
    $('#edit-user-form').submit(function(form){
        form.preventDefault();

        let id = $('#updateUserId').val();
        let data = $('#edit-user-form').serialize();

        submitform(
            '/users/'+id,
            'PUT',
            data,
            'User Successfully Updated!',
            true,
            '',
            false,
        );
        clear_errors('edit_firstname','edit_lastname','edit_role');
    });

    /*delete user*/
    $('#delete-user-form').submit(function(form){
        form.preventDefault();

        let id = $('#deleteUserId').val();
        let data = $('#delete-user-form').serialize();

        submitform(
            '/users/'+id,
            'DELETE',
            data,
            'User Successfully Deleted!',
            true,
            '',
            false,
        );
    });
});

$(document).on('click','.edit-user-btn',function(){
    let id = this.id;

    $.ajax({
        'url' : '/users/'+id,
        'type' : 'GET',
        success: function(result){
            $('#updateUserId').val(result.user.id);
            $('#edit_firstname').val(result.user.firstname);
            $('#edit_middlename').val(result.user.middlename);
            $('#edit_lastname').val(result.user.lastname);
            $('#edit_mobileNo').val(result.user.mobileNo);
            $('#edit_date_of_birth').val(result.user.date_of_birth);
            $('#edit_address').val(result.user.address);
            $('#edit_email').val(result.user.email);
            $('#edit_role').val(result.roles).change();
        }
    });
});

/*delete trigger popup*/
$(document).on('click','.delete-user-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    console.log(data);

    $('#deleteUserId').val(id);
    $('.delete-user-name').html('<strong style="color:yellow;">'+data[0]+'</strong>?');
});

$(document).on('submit','#user-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/users',
        'type' : 'POST',
        'data' : data,
        beforeSend: function () {
            $('.add-user-btn').val('Saving ... ').attr('disabled',true);
        },success: function (result) {
            console.log(result);
            if(result.success === true)
            {
                $('#user-form').trigger('reset');
                var table = $('#users-list').DataTable();
                table.ajax.reload();

                toastr.success(result.message);
                $('#add-new-user-modal').modal('toggle');
            }else if(result.success === false)
            {
                toastr.error(result.message);
            }

            $.each(result, function (key, value) {
                var element = $('#'+key);

                element.closest('div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

            $('.add-user-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

