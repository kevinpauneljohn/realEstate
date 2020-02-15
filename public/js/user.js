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

$(document).ready(function(){

    /*add user*/
    $('#user-form').submit(function(form){
        form.preventDefault();

        let data = $('#user-form').serialize();

        submitform(
            '/users',
            'POST',
            data,
            'New Permission Successfully Added!',
            true,
            '',
            false,
        );
        clear_errors('firstname','lastname','username','password','role');
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
            $('#edit_mobileNo').val(result.user.mobileNo);
            $('#edit_date_of_birth').val(result.user.date_of_birth);
            $('#edit_address').val(result.user.address);
            $('#edit_email').val(result.user.email);
            $('#edit_role').val(result.roles).change();
        }
    });
});
