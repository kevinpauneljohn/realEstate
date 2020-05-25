$(document).on('change','#project',function(){
    let id = this.value;

    $.ajax({
        'url' : '/project-model-units/'+id,
        'type' : 'GET',
        beforeSend: function(){
            $('.model-unit').remove();
            $('input, select, textarea').attr('disabled',true);
        },success: function(result){
            //console.log(result);

            $.each(result,function(key, value){
                let option = '<option value="'+value.id+'" class="model-unit">'+value.name+'</option>';
                $('#model_unit').append(option);
            });

            $('input, select, textarea').attr('disabled',false);
        },error: function(xhr,status,error){
            //console.log(xhr);
        }
    });
});

// $(document).on('change','#model_unit',function(){
//     let id = this.value;
//
//     console.log(id);
// });

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
            console.log(result);

            if(result.success === true)
            {
                let table = $('#computation-list').DataTable();
                table.ajax.reload();

                $('#computation-form').trigger('reset');
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
