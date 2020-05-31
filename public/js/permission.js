function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

$(document).on('submit','#permission-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/permissions',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.select2').val("").change();
            $('.submit-permission-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            if(result.success === true)
            {
                let table = $('#permissions-list').DataTable();
                table.ajax.reload();

                $('#permission-form').trigger('reset');
                toastr.success(result.message);

                $('#add-new-permission-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                var element = $('#'+key);

                element.closest('div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

            $('.submit-permission-btn').val('Save').attr('disabled',false);
        },error: function(xhr,status,error){
            console.log(xhr);
        }
    });
    clear_errors('permission');
});

$(document).on('submit','#edit-permission-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/permissions/'+data[2].value,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('.submit-edit-priority-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){

            if(result.success === true)
            {
                let table = $('#permissions-list').DataTable();
                table.ajax.reload();
                toastr.success(result.message);
                $('#edit-permission-modal').modal('toggle');

            }else if(result.success === false)
            {
                toastr.error(result.message);
            }

            $('.submit-edit-priority-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});


/*edit trigger popup*/
$(document).on('click','.edit-permission-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    $('#edit_permission').val(data[0]);
    $('#updatePermissionId').val(id);

    $.ajax({
        'url' : '/permission-roles',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'type' : 'POST',
        'data' : {'name':data[0]},
        success: function(result){
            $('#edit_roles').val(result).change();
        }
    });
});

/*delete trigger popup*/
$(document).on('click','.delete-permission-btn',function () {
    let id = this.id;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                'url' : '/permissions/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        let table = $('#permissions-list').DataTable();
                        table.ajax.reload();

                        Swal.fire(
                            'Deleted!',
                            'Permission has been deleted.',
                            'success'
                        );
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });

        }
    });
});
