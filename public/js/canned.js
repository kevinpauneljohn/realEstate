$(document).on('submit','#add-canned-message-form',function(form){
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
                $('#add-canned-message-modal').modal('toggle');

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

$(document).on('submit','#edit-canned-message-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/canned/'+data[1].value,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function () {
            $('.submit-form-btn').val('Saving ... ').attr('disabled',true);
        },success: function (result) {
            console.log(result);

            if(result.success === true)
            {
                $('.add-canned-message').trigger('reset');
                $('#add-canned-message-modal').modal('toggle');

                var table = $('#canned-messages-list').DataTable();
                table.ajax.reload();

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

$(document).on('click','.edit-canned',function(){
    let id = this.id, form = $('.add-canned-message');
    let item = '<input type="hidden" name="_method" value="PUT"><input type="hidden" name="canned_id" value="'+id+'">';

    $('.text-danger').remove();
    form.prepend(item);

    $('.add-canned-message .modal-title').text('Edit Canned Message');
    form.removeAttr('id').attr('id','edit-canned-message-form');

    $.ajax({
        'url' : '/canned/'+id,
        'type' : 'GET',
        beforeSend: function(){
            $('input, select, textarea, button').attr('disabled',true);
        },success: function(result){
            $('#status').val(result.status).change();
            $('#category').val(result.canned_categories_id).change();
            $('#title').val(result.title);
            $('#body').val(function () {
                let regex = /(<([^>]+)>)/ig
                let body = result.body;
                let content = body.replace(regex, "");

                return content;
            });

            $('input, select, textarea, button').attr('disabled',false);
        }
    });
});

$(document).on('click','.add-canned-btn',function(){
    let form = $('.add-canned-message');

    $('.add-canned-message').trigger('reset');
    $('input[name=_method], input[name=canned_id], .text-danger').remove();
    $('.add-canned-message .modal-title').text('Add New Canned Message');
    form.removeAttr('id').attr('id','add-canned-message-form');
});
