$(document).on('submit','#notes-form',function (form) {
    form.preventDefault();

    let data = $('#notes-form').serializeArray();

    $.ajax({
        'url' : '/lead-notes',
        'type' : 'post',
        'data' : data,
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        }
        ,success: function(result){
            if(result.success === true)
            {
                let item = $('<div class="col-lg-12" id="note-row-'+result.note.id+'">' +
                    '<div class="info-box bg-light">' +
                    '<div class="info-box-content">' +
                    '<div class="row" id="lead-note-'+result.note.id+'">' +
                    '<span class="col-lg-11" id="note-list-'+result.note.id+'">' +
                    '<span class="info-box-text text-muted">Note added '+result.note.updated_at+'</span>' +
                    '<span class="info-box-number text-muted mb-0" id="note-content-'+result.note.id+'">'+result.note.notes+'</span>' +
                    '</span>' +
                    '<span class="col-lg-1">' +
                    '<button type="button" class="btn btn-primary btn-xs edit-note" id="'+result.note.id+'"><i class="fa fa-edit"></i></button>' +
                    '<button type="button" class="btn btn-danger btn-xs delete-note" id="'+result.note.id+'"><i class="fa fa-trash"></i></button>' +
                    '</span></div>' +
                    '</div></div></div>').hide()
                    .fadeIn(800);

                $('.note-lists').prepend(item);
                $('#notes-form').trigger('reset');
                $('.note-count').text('('+result.count+')');
                toastr.success(result.message);
            }
            $('.submit-form-btn').attr('disabled',false);
            $('.spinner').hide();
        }
        ,error: function (xhr, status, error) {
        console.log(xhr);
        }
    });
    clear_errors('notes');
});
$(document).on('click','.edit-note',function(){
    let id = this.id;
    let note = $('#note-content-'+id);
    let content = note.text();
    let item = $('<form class="form-note-class" id="form-note-'+id+'">' +
        '<input type="hidden" name="_method" value="PUT">' +
        '<input type="hidden" name="leadNoteId" value="'+id+'">' +
        '<textarea class="form-control" name="note">'+content+'</textarea>' +
        '<input type="submit" class="btn btn-primary btn-sm float-right" value="Save">' +
        '<button type="button" class="btn btn-warning btn-sm float-right cancel-btn" id="'+id+'">Cancel</button></form>');

    $('#lead-note-'+id+' .edit-note').attr('disabled',true);
    note.hide();
    $('#note-list-'+id).append(item);
});

$(document).on('click','.cancel-btn',function(){
    let id = this.id;
    let note = $('#note-content-'+id);

    $('#form-note-'+id).remove();
    $('#lead-note-'+id+' .edit-note').removeAttr('disabled');
    note.show();
});

$(document).on('submit','.form-note-class',function (form) {
    form.preventDefault()
    let data = $(this).serializeArray();
    let id = this.id;

    $.ajax({
        'url' : '/lead-notes/'+data[1].value,
        'type' : 'PUT',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : data,
        beforeSend: function(){

        },success: function(result){
            if(result.hasOwnProperty('note'))
            {
                $('#'+id+' textarea').after('<span id="note-error-'+data[1].value+'" class="text-danger">'+result.note[0]+'</span>');
            }else{
                $('#note-error-'+data[1].value).remove();
            }

            if(result.success === true)
            {
                $('#form-note-'+data[1].value).remove();
                $('#lead-note-'+data[1].value+' .edit-note').removeAttr('disabled');
                $('#note-content-'+data[1].value).text(data[2].value).show();
                toastr.success(result.message);
            }else if(result.success === false){
                toastr.error(result.message);
            }

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('click','.delete-note',function(){
    let id = this.id,
        leadId = $('input[name=leadId]').val();

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
                'url' : '/lead-notes/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id,'leadId' : leadId},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        $('#note-row-'+id).remove();
                        $('.note-count').text('('+output.count+')');

                        Swal.fire(
                            'Deleted!',
                            'Note has been deleted.',
                            'success'
                        );
                    }
                },error: function(xhr, status, error){
                console.log(xhr);
            }
            });

        }
    });
});
