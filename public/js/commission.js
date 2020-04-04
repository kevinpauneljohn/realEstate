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

    $('#add-commission-form').submit(function (form) {
        form.preventDefault();

        let data = $('#add-commission-form').serialize();
        submitform(
            '/commissions',
            'POST',
            data,
            'Commission Successfully Added!',
            true,
            '',
            false,
        );
    });
});


$(document).on('change','#project',function(){
    let value = this.value;

    if(value != "")
    {
        $.ajax({
            'url' : '/upline-commission/'+value,
            'type' : 'GET',
            beforeSend: function(){
                $('#commission_rate').html("");
            },
            success: function (result) {
                $('#commission_rate').append('<option value=""> -- Select -- </option>');
                $.each(result, function (key, value) {
                    $('#commission_rate').append('<option value="'+value+'">'+value+'%</option>');
                });
            }
        });
    }
});
