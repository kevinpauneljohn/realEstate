$(document).on('submit','#builder-member-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/add-member/builder',
        'type' : 'POST',
        'data' : data,
        success: function(result){
            console.log(result);
        }
    });
});
