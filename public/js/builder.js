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

    clear_errors('name');
});

$(document).on('click','.edit-btn',function () {
    rowId = this.id;
    $.get('/builders/'+rowId+'/edit',function (data) {
        let parentElm = '#edit-builder-form';

        $(parentElm+' #edit_name').val(data.name);
        $(parentElm+' #edit_name').val(data.name);
        $(parentElm+' #edit_name').val(data.name);
        $(parentElm+' #edit_remarks').val(data.remarks);
    });
});

$(document).on('click','.delete-btn',function(){
    rowId = this.id;

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
                'url' : '/builders/'+rowId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE'},
                beforeSend: function(){

                },success: function(output){
                    //console.log(output);
                    if(output.success === true){
                        $('#builder-list').DataTable().ajax.reload();
                        Swal.fire(
                            'Deleted!',
                            'Builder has been deleted.',
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

$(document).on('submit','#edit-builder-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/builders/'+rowId,
        'type': 'PUT',
        'data' : data,
        beforeSend: function () {
            $('.builder-form-btn').attr('disabled',true).val('Saving...');
        },success: function(result){
            //console.log(result);

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
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.builder-form-btn').attr('disabled',false).val('Save');
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
        }
    });
    clear_errors('edit_name');
});

