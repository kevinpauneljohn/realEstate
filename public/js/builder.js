let rowId;

function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}


$(document).on('submit','#add-builder-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/builders',
        'type': 'POST',
        'data': data,
        beforeSend: function(){
            $('.builder-form-btn').attr('disabled',true).val('Saving...');
        },success: function(result){
            if(result.success === true)
            {
                $('#add-builder-form').trigger('reset');
                $('.textarea').summernote('reset');
                toastr.success(result.message);

                $('#builder-list').DataTable().ajax.reload();

                $('#add-new-builder-modal').modal('toggle');
            }
            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.builder-form-btn').attr('disabled',false).val('Save');
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
        }
    });

    clear_errors('name','address');
});

$(document).on('click','.edit-btn',function () {
    rowId = this.id;
    let parentElm = '#edit-builder-form';

    $.ajax({
        'url' : '/builders/'+rowId+'/edit',
        'type' : 'GET',
        beforeSend: function(){
            $(parentElm+' input, '+parentElm+' textarea').attr('disabled',true);
        },success: function(data){
            $(parentElm+' #edit_name').val(data.name);
            $(parentElm+' #edit_address').val(data.address);
            $(parentElm+' #edit_description').val(data.description);
            $(parentElm+' input, '+parentElm+' textarea').attr('disabled',false);
        },error: function (xhr, status, error) {
            console.log(xhr);
        }
    });
});



$(document).on('submit','#edit-builder-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/builders/'+rowId,
        'type': 'PUT',
        'data' : data,
        beforeSend: function () {
            $('.builder-form-btn').attr('disabled',true).val('Saving...');
        },success: function(result, status, xhr){
            if(result.success === true)
            {
                toastr.success(result.message);

                $('#builder-list').DataTable().ajax.reload();
                $('#edit-builder-modal').modal('toggle');
            }else if(result.success === false)
            {
                toastr.error(result.message);
            }
            $.each(result, function (key, value) {
                let element = $('.edit_'+key);

                element.find('.error-edit_'+key).remove();
                element.append('<p class="text-danger error-edit_'+key+'">'+value+'</p>');
            });

            $('.builder-form-btn').attr('disabled',false).val('Save');
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
        }
    });
    clear_errors('edit_name','edit_address');
});

