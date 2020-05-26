$(document).on('click','.accordion-dropdown',function(){
    let id = this.id;
    if($(this).hasClass('collapsed'))
    {
        $('#'+id).html('<i class="fas fa-plus"></i>');
    }else{
        $('#'+id).html('<i class="fas fa-minus"></i>');
    }
});

$(document).on('click','.copy-canned',function(){
    let id = this.id;
    copyToClipboard('#canned-body-'+id,'Canned message was copied');
});
function copyToClipboard(element,message) {
    var $temp = $("<textarea>");
    $("body").append($temp);
    $temp.val($(element).text().trim()).select();
    document.execCommand("copy");
    toastr.success(message);
    $temp.remove();
}

$(document).on('click','.copy-computation',function(){
    let id = this.id;
    copyToClipboard('#computation-'+id,'Computation was copied');
});

$(document).on('submit','.sample-computation-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();
    $.ajax({
        'url' : '/sample-computation',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.search-btn').val('Searching ... ').attr('disabled',true);
            $('.computation-displayed').remove();
        },success: function(result){

            $.each(result,function(key, value){

                let item = '<div class="row computation-displayed">' +
                    '<div class="col-lg-12">' +
                    '<div class="callout callout-info">' +
                    '<button class="btn btn-success btn-sm float-right copy-computation" id="'+value.id+'"><i class="fa fa-copy"></i> Copy</button>' +
                    '<div id="computation-'+value.id+'"><h5>Project: '+value.project_id+'<br/>Model Unit: '+value.model_unit_id+'</h5>' +
                    '<span class="unit-type-title">'+value.location_type+'</span><br/>' +
                    '<strong class="financing-title">'+value.financing+'</strong>' +
                    '<p>'+value.computation+'</p>' +
                    '</div></div></div></div>';
                $('.display-computation').append(item);
            });

            $('.search-btn').val('Search').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('change','#project_label',function(){
    let id = this.value;

    $.ajax({
        'url' : '/project-model-units/'+id,
        'type' : 'GET',
        beforeSend: function(){
            $('.model-unit-label').remove();
            $('#model_unit_label,#unit_type_label,#financing_label,.search-btn').attr('disabled',true);
        },success: function(result){
            //console.log(result);

            $.each(result,function(key, value){
                let option = '<option value="'+value.id+'" class="model-unit-label">'+value.name+'</option>';
                $('#model_unit_label').append(option);
            });

            $('#model_unit_label,#unit_type_label,#financing_label,.search-btn').attr('disabled',false);
        },error: function(xhr,status,error){
            //console.log(xhr);
        }
    });
});
