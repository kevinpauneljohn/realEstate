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

$(document).on('change','#calculator-template',function(){
    let value = this.value;

    $.ajax({
        'url' : '/calculator',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'template' : value},
        beforeSend: function(){
            $('#calculator-template').attr('disabled',true).after('<div class="load-template" style="margin: auto;width: 10%;margin-top:20px;">' +
                '<div class="spinner-border text-primary" role="status">' +
                '<span class="sr-only">Loading...</span>' +
                '</div>' +
                '</div>');
            $('.display-calculator').html("");
        },success: function(result){
            $('.display-calculator').html(result);
            $('#calculator-template').attr('disabled',false);
            $('.load-template').remove();
        },error: function(xhr, status, error){

        }
    });

});

$(document).on('change','#requirement-template',function(){
    let value = this.value;

    if(value !== "")
    {
        $.ajax({
            'url' : '/template/'+value,
            'type' : 'POST',
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            'data' : {'id' : value},
            beforeSend: function(){
                $('#requirement-template').attr('disabled',true).after('<div class="load-template" style="margin: auto;width: 10%;margin-top:20px;">' +
                    '<div class="spinner-border text-primary" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>');
                $('.requirement-display').html("");
            },success: function (result) {
                //console.log(result);
                $('.requirement-display').html('<div class="callout callout-info" style="margin-top:10px;width: 100%;"></div>');
                $.each(result, function(key, value){
                    $('.requirement-display .callout').append('<span>- '+value.description+'</span><br/>');
                });

                $('#requirement-template').attr('disabled',false);
                $('.load-template').remove();
            },error: function(xhr,status,error){
                console.log(xhr);
            }
        });
    }else{
        $('.requirement-display').html("");
    }
});

