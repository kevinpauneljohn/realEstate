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

    let addForm = $('#add-requirements-form');
    addForm.submit(function(form){
        form.preventDefault();

        let data = addForm.serialize();

        submitform(
            '/requirements/',
            'POST',
            data,
            'Requirements Successfully Added!',
            false,
            '',
            true,
        );
    });
});

$(document).on('click','.row-description-btn',function(){
    let value = this.value;

    let row = $('.row-description');

    if(value == 'plus')
    {
        $('.desc-inputs').append('<div class="row row-description">\n' +
            '                                    <div class="col-sm-9">\n' +
            '                                        <input type="text" name="description[]" class="form-control description"/>\n' +
            '                                    </div>\n' +
            '                                    <div class="col-sm-3">\n' +
            '                                        <button type="button" class="btn btn-success row-description-btn" value="plus"><i class="fa fa-plus"></i></button>\n' +
            '                                        <button type="button" class="btn btn-danger row-description-btn" value="minus"><i class="fa fa-minus"></i></button>\n' +
            '                                    </div>\n' +
            '                                </div>');
    }else{
        this.closest('.row-description').remove();
    }
    console.log(value);
});
