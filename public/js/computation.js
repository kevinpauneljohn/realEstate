$(document).on('change','#project',function(){
    let id = this.value;

    $.ajax({
        'url' : '/project-model-units/'+id,
        'type' : 'GET',
        beforeSend: function(){
            $('.model-unit').remove();
        },success: function(result){
            //console.log(result);

            $.each(result,function(key, value){
                let option = '<option value="'+value.id+'" class="model-unit">'+value.name+'</option>';
                $('#model_unit').append(option);
            });
        },error: function(xhr,status,error){
            //console.log(xhr);
        }
    });
});

$(document).on('change','#model_unit',function(){
    let id = this.value;

    console.log(id);
});
