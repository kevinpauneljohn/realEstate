$(document).on('submit','#documentation-form',function(form){
    form.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type:'POST',
        url: '/documentation',
        data: new FormData(this),
        cache:false,
        contentType: false,
        processData: false,
        beforeSend: function(){
            $('#documentation-form input,#documentation-form textarea').attr('disabled',true);
            $('.submit-documentation-btn').val('Saving ...');
        },
        success: (data) => {
            console.log(data);

            $.each(data, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('#documentation-form input,#documentation-form textarea').attr('disabled',false);
            $('.submit-documentation-btn').val('Save');
        },
        error: function(data){
            console.log(data);
        }
    });
});

