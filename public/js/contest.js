$(document).on('submit','#contest-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/contest',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){

        },success: function(result){
            console.log(result);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});
