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
    /*add project*/
    $('#add-unit-form').submit(function (form) {
        form.preventDefault();

        let data = $('#add-unit-form').serialize();

        submitform(
            '/model-units',
            'POST',
            data,
            'New Model Unit Successfully Added!',
            true,
            '',
            false,
        );
    });
});

$(document).on('submit','#add-model-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/model-units',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.submit-model-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){

            if(result.success === true)
            {
                var table = $('#model-units-list').DataTable();
                table.ajax.reload();
                $('#add-model-form').trigger('reset');
                $('#add-new-model-modal').modal('toggle');
                toastr.success(result.message);
            }
            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.submit-model-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('model_name','house_type','floor_level','lot_area','floor_area','photo_url','remarks')
});

$(document).on('submit','#edit-model-form',function (form) {
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/model-units/'+data[3].value,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('#edit-model-form input, #edit-model-form select, #edit-model-form textarea').attr('disabled',true);
        },success: function(result){
            console.log(result);

            if(result.success === true)
            {
                let table = $('#model-units-list').DataTable();
                table.ajax.reload();
                $('#edit-model-modal').modal('toggle');
                toastr.success(result.message);
            }else if(result.success === false)
            {
                toastr.error(result.message);
            }
            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('#edit-model-form input, #edit-model-form select, #edit-model-form textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('edit_model_name','edit_house_type','edit_floor_level','edit_lot_area','edit_floor_area','edit_photo_url','edit_remarks')
});

$(document).on('click','.update-btn',function(){
    let id = this.id;
    $('#model_id').val(id);

    $.ajax({
        'url' : '/model-unit-details/'+id,
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function(){
            $('#edit-model-form input, #edit-model-form select, #edit-model-form textarea').attr('disabled',true);
        },success: function(result){

            $('#edit_model_name').val(result.name);
            $('#edit_house_type').val(result.house_type).change();
            $('#edit_floor_level').val(result.floor_level).change();
            $('#edit_lot_area').val(result.lot_area);
            $('#edit_floor_area').val(result.floor_area);
            $('#edit_photo_url').val(result.description.photo_url);
            $('#edit_remarks').val(result.description.description);

            $('#edit-model-form input, #edit-model-form select, #edit-model-form textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});


