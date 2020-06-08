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
    // $('#add-project-form').submit(function (form) {
    //     form.preventDefault();
    //
    //     let data = $('#add-project-form').serialize();
    //     submitform(
    //         '/projects',
    //         'POST',
    //         data,
    //         'New Project Successfully Added!',
    //         true,
    //         '',
    //         false,
    //     );
    // });

    // /*edit project*/
    // $('#edit-project-form').submit(function (form) {
    //     form.preventDefault();
    //
    //     let data = $('#edit-project-form').serialize();
    //     let id = $('#updateProjectId').val();
    //     submitform(
    //         '/projects/'+id,
    //         'PUT',
    //         data,
    //         'Project Successfully Edited!',
    //         true,
    //         '',
    //         false,
    //     );
    // });

    // /*delete project*/
    // $('#delete-project-form').submit(function (form) {
    //     form.preventDefault();
    //
    //     let data = $('#delete-project-form').serialize();
    //     let id = $('#deleteProjectId').val();
    //     submitform(
    //         '/projects/'+id,
    //         'DELETE',
    //         data,
    //         'Project Successfully Deleted!',
    //         true,
    //         '',
    //         false,
    //     );
    // });
});

$(document).on('submit','#add-project-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();
    console.log(data);

    $.ajax({
        'url' : '/projects',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.project-form-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            console.log(result);

            if(result.success === true)
            {
                let table = $('#projects-list').DataTable();
                table.ajax.reload();

                $('#add-project-form').trigger('reset');
                toastr.success(result.message);
                $('#add-new-project-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.project-form-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('name','address','commission_rate');
});

$(document).on('submit','#edit-project-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();
    console.log(data);

    $.ajax({
        'url' : '/projects/'+data[2].value,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('.edit-form-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            console.log(result);

            if(result.success === true)
            {
                let table = $('#projects-list').DataTable();
                table.ajax.reload();

                toastr.success(result.message);
                $('#edit-project-modal').modal('toggle');
            }else if(result.success === false)
            {
                toastr.error(result.message);
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.edit-form-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('edit_name','edit_address','edit_commission_rate');
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
            $('#edit_remarks').val(result.remarks);
        }
    });
});

/*delete trigger popup*/
$(document).on('click','.delete-project-btn',function () {
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
                'url' : '/projects/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        Swal.fire(
                            'Deleted!',
                            output.message,
                            'success'
                        );

                        let table = $('#projects-list').DataTable();
                        table.ajax.reload();
                    }else{
                        toastr.error(output.message);
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });

        }
    });
});
