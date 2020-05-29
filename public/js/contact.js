$(document).on('submit','#contact-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url': '/contacts',
        'type': 'POST',
        'data': data,
        beforeSend: function () {
            $('.submit-contact-btn').val('Saving ... ').attr('disabled',true);
        }, success: function (result) {
            console.log(result);

            if(result.success === true)
            {
                toastr.success(result.message);
                let table = $('#contact-list').DataTable();
                table.ajax.reload();
                $('#contact-form').trigger('reset');
                $('#add-contacts-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
            $('.submit-contact-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });

    clear_errors('title','contact_person','contact_details');
});

$(document).on('click','.edit-contacts-btn',function(){
    let id = this.id;
    $('#updateContactId').val(id);
    $.ajax({
        'url' : '/contacts/'+id,
        'type' : 'GET',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function(){
            $('#edit-contacts-form input,#edit-contacts-form textarea').attr('disabled',true);
        },success: function(result){

            $('#edit_title').val(result.title);
            $('#edit_contact_person').val(result.contact_person);
            $('#edit_contact_details').val(function(){
                let regex = /(<([^>]+)>)/ig
                let body = result.contact_details;
                let content = body.replace(regex, "");

                return content;
            });

            $('#edit-contacts-form input,#edit-contacts-form textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }

    });
});

$(document).on('submit','#edit-contacts-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/contacts/'+data[2].value,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('.submit-edit-contact-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            console.log(result);

            if(result.success === true)
            {
                toastr.success(result.message);
                let table = $('#contact-list').DataTable();
                table.ajax.reload();
                $('#edit-contacts-modal').modal('toggle');
            }else if(result.success === false)
            {
                toastr.error(result.message);
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.submit-edit-contact-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });

    clear_errors('edit_title','edit_contact_person','edit_contact_details');
});

$(document).on('click','.view-contacts-btn',function(){
    let id = this.id;
    $.ajax({
        'url' : '/contacts/'+id,
        'type' : 'GET',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function(){
            $('#view-contacts-modal .modal-body').after('<div class="load-template" style="margin: auto;width: 10%;margin-bottom: 20px;">' +
                '<div class="spinner-border text-primary" role="status">' +
                '<span class="sr-only">Loading...</span>' +
                '</div>' +
                '</div>');
            $('#view-contacts-modal .modal-body').html("");
        },success: function(result){
                console.log(result);

                let item = '<strong>'+result.title+'</strong><p><strong>Contact Person</strong>: '+result.contact_person+'<br/><br/><span>'+result.contact_details+'</span></p>';

                $('#view-contacts-modal .modal-body').append(item);

            $('.load-template').remove();
        },error: function(xhr, status, error){
            console.log(xhr);
        }

    });
});

$(document).on('click','.delete-contacts',function(){
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
                'url' : '/contacts/'+id,
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

                        let table = $('#contact-list').DataTable();
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
