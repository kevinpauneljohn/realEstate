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

$(document).on('submit','#add-project-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/dhg-projects',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.dhg-project-form-btn').attr('disabled',true).val('Saving...');
        },success: function(result){
            if(result.success === true)
            {
                $('#add-project-form').trigger('reset');
                $('.textarea').summernote('reset');
                $('#add-project-form .select2').val('').trigger('change');
                $('.text-danger').remove();
                toastr.success(result.message);

                $('#project-list').DataTable().ajax.reload();

                $('#add-new-project-modal').modal('toggle');
            }
            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.dhg-project-form-btn').attr('disabled',false).val('Save');
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
            $('.dhg-project-form-btn').attr('disabled',false).val('Save');
        }
    });
});

$(document).on('click','.edit-btn', function(){
    rowId = this.id;

    $.ajax({
        'url' : '/dhg-projects/'+rowId+'/edit',
        'type' : 'GET',
        beforeSend: function(){
            $('#edit-project-form input, #edit-project-form select, #edit-project-form textarea').attr('disabled',true);
        },success: function(result){

            $('#edit_client').val(result.user_id).change();
            $('#edit_architect').val(result.architect_id).change();
            $('#edit_builder').val(result.builder_id).change();
            $('#edit_agent').val(result.agent_id).change();
            $('#edit_address').val(result.address);
            $('#edit_lot_price').val(result.lot_price);
            $('#edit_lot_price').val(result.lot_price);
            $('#edit_description').summernote('code',result.description);


            $('#edit-project-form input, #edit-project-form select, #edit-project-form textarea').attr('disabled',false);
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
            $('#edit-project-form input, #edit-project-form select, #edit-project-form textarea').attr('disabled',false);
        }
    });
});

$(document).on('submit','#edit-project-form', function (form) {
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/dhg-projects/'+rowId,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('.dhg-project-form-btn').attr('disabled',true).val('Saving...');
        },success: function(result){

            if(result.success === true)
            {
                toastr.success(result.message);

                $('#project-list').DataTable().ajax.reload();

                $('#edit-project-modal').modal('toggle');
            }else if(result.success === false && result.change === false)
            {
                toastr.warning(result.message);
            }else{
                toastr.danger(result.message);
            }
            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.dhg-project-form-btn').attr('disabled',false).val('Save');
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
            $('.dhg-project-form-btn').attr('disabled',false).val('Save');
        }
    });
});
