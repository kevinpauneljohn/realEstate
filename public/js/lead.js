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


    /*delete user*/
    $('#delete-lead-form').submit(function(form){
        form.preventDefault();

        let id = $('#deleteLeadId').val();
        let data = $('#delete-lead-form').serialize();

        submitform(
            '/leads/'+id,
            'DELETE',
            data,
            'New Permission Successfully Deleted!',
            true,
            '',
            false,
        );
    });
});


/*delete trigger popup*/
$(document).on('click','.delete-lead-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();


    $('.delete-lead-name').html('<strong style="color:yellow;">'+data[1]+' '+data[2]+'</strong>?');
    $('.lead-details').html('<table>' +
        '<tr><td>Mobile No.</td><td>'+data[3]+'</td></tr>' +
        '<tr><td>Email</td><td>'+data[4]+'</td></tr>' +
        '<tr><td>Point Of Contact</td><td>'+data[5]+'</td></tr></table>');
    $('#deleteLeadId').val(id);
});
