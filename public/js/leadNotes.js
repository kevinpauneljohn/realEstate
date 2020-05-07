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
            console.log(result);
            if(result.success === true)
            {
                $('.note-lists').prepend('<div class="col-lg-12">' +
                    '<div class="info-box bg-light">' +
                    '<div class="info-box-content">' +
                    '<div class="row">' +
                    '<span class="col-lg-11">' +
                    '<span class="info-box-text text-muted">Note added '+result.note.updated_at+'</span>' +
                    '<span class="info-box-number text-muted mb-0">'+result.note.notes+'</span>' +
                    '</span>' +
                    '<span class="col-lg-1">' +
                    '<button type="button" class="btn btn-danger btn-xs" id="'+result.note.id+'"><i class="fa fa-trash"></i></button>' +
                    '<button type="button" class="btn btn-primary btn-xs" id="'+result.note.id+'"><i class="fa fa-edit"></i></button>' +
                    '</span></div>' +
                    '</div></div></div>');
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
