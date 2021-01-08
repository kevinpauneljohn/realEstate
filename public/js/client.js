$(document).on('submit','#client-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url'   : '/clients',
        'type'  : 'POST',
        'data'  : data,
        beforeSend: function () {
            $('.submit-contact-btn').val('Saving ... ').attr('disabled',true);
        },success: function (result) {

            if(result.success === true)
            {
                toastr.success(result.message);
                $('#client-form').trigger('reset');
                $('#add-client-modal').modal('toggle');

                let table = $('#client-list').DataTable();
                table.ajax.reload();
            }
            $('.submit-contact-btn').val('Save').attr('disabled',false);
            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

        },error: function(xhr, status, error){
            console.log(xhr);
            $('.submit-contact-btn').val('Save').attr('disabled',false);
        }
    });

    clear_errors('firstname','lastname','address','username','password','email');
});

$(document).on('click','.edit-client-btn',function(){
    let id = this.id;
    $('#updateClientId').val(id);
    $.ajax({
        'url' : '/client-info/'+id,
        'type' : 'GET',
        beforeSend: function(){
            $('#edit-client-form input, #edit-client-form textarea').attr('disabled',true);
        },success: function(result){
            $('#edit_firstname').val(result.firstname);
            $('#edit_middlename').val(result.middlename);
            $('#edit_lastname').val(result.lastname);
            $('#edit_address').val(result.address);

            $('#edit-client-form input, #edit-client-form textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('submit','#edit-client-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();
    //console.log(data[2].value);
    $.ajax({
        'url'   : '/clients/'+data[2].value,
        'type'  : 'PUT',
        'data'  : data,
        beforeSend: function () {
            $('#edit-client-form input, #edit-client-form textarea').attr('disabled',true);
            $('.submit-edit-client-btn').val('Saving ...');
        },success: function(result){
            if(result.success === true)
            {
                toastr.success(result.message);
                $('#edit-client-modal').modal('toggle');

                let table = $('#client-list').DataTable();
                table.ajax.reload();
            }else if(result.success === false)
            {
                toastr.error(result.message);
            }

            $.each(result, function (key, value) {
                let element = $('.edit_'+key);

                element.find('.error-edit_'+key).remove();
                element.append('<p class="text-danger error-edit_'+key+'">'+value+'</p>');
            });

            $('#edit-client-form input, #edit-client-form textarea').attr('disabled',false);
            $('.submit-edit-client-btn').val('Save');
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('edit_firstname','edit_lastname','edit_address');
});


