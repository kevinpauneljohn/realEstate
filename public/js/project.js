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

$(document).ready(function () {

    /*add project*/
    $('#add-project-form').submit(function (form) {
        form.preventDefault();

        let data = $('#add-project-form').serialize();
        submitform(
            '/projects',
            'POST',
            data,
            'New Project Successfully Added!',
            true,
            '',
            false,
        );
    });

    /*edit project*/
    $('#edit-project-form').submit(function (form) {
        form.preventDefault();

        let data = $('#edit-project-form').serialize();
        let id = $('#updateProjectId').val();
        submitform(
            '/projects/'+id,
            'PUT',
            data,
            'Project Successfully Edited!',
            true,
            '',
            false,
        );
    });

    /*delete project*/
    $('#delete-project-form').submit(function (form) {
        form.preventDefault();

        let data = $('#delete-project-form').serialize();
        let id = $('#deleteProjectId').val();
        submitform(
            '/projects/'+id,
            'DELETE',
            data,
            'Project Successfully Deleted!',
            true,
            '',
            false,
        );
    });
});

$(document).on('click','.edit-project-btn',function(){
    let id = this.id;

    $('#updateProjectId').val(id);
    $.ajax({
        'url'   : '/projects/'+id,
        'type'  : 'GET',
        success: function(result){
            $('#edit_name').val(result.name);
            $('#edit_address').val(result.address);
            $('#edit_commission_rate').val(result.commission_rate);
            $('#edit_remarks').summernote("code", result.remarks);
        }
    });
});

/*delete trigger popup*/
$(document).on('click','.delete-project-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();


    $('.delete-project-name').html('<strong style="color:yellow;">'+data[0]+'</strong>?');
    $('#deleteProjectId').val(id);
});
