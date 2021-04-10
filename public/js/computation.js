$(document).on('change','#project',function(){
    let id = this.value;

    $.ajax({
        'url' : '/project-model-units/'+id,
        'type' : 'GET',
        beforeSend: function(){
            $('.model-unit').remove();
            $('#model_unit,#unit_type,#financing,#computation,.save-btn').attr('disabled',true);
        },success: function(result){
            //console.log(result);

            $.each(result,function(key, value){
                let option = '<option value="'+value.id+'" class="model-unit">'+value.name+'</option>';
                $('#model_unit').append(option);
                // $('#edit_model_unit').append(option);
            });

            $('#model_unit,#unit_type,#financing,#computation,.save-btn').attr('disabled',false);
        },error: function(xhr,status,error){
            //console.log(xhr);
        }
    });
});

$(document).on('submit','#computation-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/computations',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.save-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            //console.log(result);

            if(result.success === true)
            {
                let table = $('#computation-list').DataTable();
                table.ajax.reload();

                $('#computation-form').trigger('reset');
                $('#project').val("").change();
                $('.model-unit').remove();
                $('#add-new-computation-modal').modal('toggle');

                toastr.success(result.message);
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.save-btn').val('Save').attr('disabled',false);
        },error: function(xhr,status,error){
            console.log(xhr);
        }
    });

    clear_errors('project','model_unit','financing','computation');
});

$(document).on('click','.edit-computation-btn',function(){
    let id = this.id;
    $('.form-submit input[name=_method],.form-submit input[name=id]').remove();
    $('#add-new-computation-modal .modal-title').text('Edit Computation');
    $('.text-danger').remove();
    $('#add-new-computation-modal').modal('toggle');

    $.ajax({
        'url' : '/computations/'+id,
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function(){
            $('.form-submit').removeAttr('id').attr('id','edit-computation-form').prepend('<input type="hidden" name="_method" value="PUT"><input type="hidden" name="id" value="'+id+'">');
            $('#edit-computation-form input,#edit-computation-form select,#edit-computation-form textarea').attr('disabled',true);
        },success: function(result){
            console.log(result);

            $('#project').val(result.details.project_id);
            $('#unit_type').val(result.details.location_type).change();
            $('#financing').val(result.details.financing).change();
            $('#computation').val(function () {
                let regex = /(<([^>]+)>)/ig
                let body = result.details.computation;
                let content = body.replace(regex, "");

                return content;
            });
            $('.model_unit').html('<label for="model_unit">Model Unit</label><span class="required">*</span><select class="form-control" id="model_unit" name="model_unit" style="width: 100%;"><option value=""> -- Select -- </option></select>');
            let selected;
            $.each(result.modelUnit, function(key, value){
                if(result.details.model_unit_id === value.id)
                {
                    selected = "selected";
                }else{
                    selected = "";
                }
                $('#model_unit').append('<option value="'+value.id+'" class="model-unit" '+selected+'>'+value.name+'</option>');
                console.log(result.details.model_unit_id === value.id);
            });
            $('#edit-computation-form input,#edit-computation-form select,#edit-computation-form textarea').attr('disabled',false);
        },error: function(xhr,status,error){
            console.log(xhr);
        }
    });
});

$(document).on('click','.add-computation-btn',function(){
    $('#add-new-computation-modal .modal-title').text('Add Computation');
    $('.form-submit input[name=_method],.form-submit input[name=id]').remove();
    $('.form-submit').removeAttr('id').attr('id','computation-form').trigger('reset');
    $('.text-danger').remove();
    $('#project,#unit_type,#financing').val("").change();
    $('.edit_model_unit').removeClass('edit_model_unit').addClass('model_unit').html('<label for="model_unit">Model Unit</label><span class="required">*</span><select class="form-control" id="model_unit" name="model_unit" style="width: 100%;"><option value=""> -- Select -- </option></select>');
    $('#add-new-computation-modal').modal('toggle');
});

$(document).on('submit','#edit-computation-form',function (form) {
    form.preventDefault();

    let data = $(this).serializeArray(),
        id = data[1].value;

    $.ajax({
        'url'   : '/computations/'+id,
        'type'  : 'PUT',
        'data'  : data,
        beforeSend: function(){
            $('.save-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            console.log(result);

            if(result.success === true)
            {
                let table = $('#computation-list').DataTable();
                table.ajax.reload();

                $('#add-new-computation-modal').modal('toggle');

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

            $('.save-btn').val('Save').attr('disabled',false);
        },error: function(xhr,status,error){
            console.log(xhr);
        }
    });

    clear_errors('project','model_unit','financing','computation');
});

$(document).on('click','.delete-computation-btn',function(){
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
                'url' : '/computations/'+id,
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

                        let table = $('#computation-list').DataTable();
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
