$(document).on('submit','.add-canned-message',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/canned',
        'type' : 'POST',
        'data' : data,
        beforeSend: function () {
            $('.submit-form-btn').val('Saving ... ').attr('disabled',true);
        },success: function (result) {
            console.log(result);

            if(result.success === true)
            {
                $('.add-canned-message').trigger('reset');

                var table = $('#canned-messages-list').DataTable();
                table.ajax.reload();

                toastr.success(result.message);
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.submit-form-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('title','category','body');
});

$(document).on('click','.dropdown-menu .dropdown-item',function(){
    let shortcode = this.text
    let body = $("#body");
    let caretPos = body[0].selectionStart;
    let textAreaTxt = body.val();
    let txtToAdd = shortcode;

    body.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
});

$(document).on('click','.delete-canned',function(){
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
                'url' : '/canned/'+id,
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

                        let table = $('#canned-messages-list').DataTable();
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
