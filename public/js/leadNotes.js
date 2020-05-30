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

$(document).on('click','.view-btn',function(){
    let id = this.id, item;

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    item = '<table class="reminder-schedule"><tr><td><strong>Category: </strong><span style="color:green;">'+data[2]+'</span> / ' +
        '<span><strong>Schedule:</strong> <span style="color:green;">'+data[0]+'</span></span> / <strong>'+data[1]+'</strong></td></tr></table>';
    console.log(data);
    Swal.fire({
        title: item,
        html: $('#hidden-value-'+id).val()
    });
});


$(document).on('submit','#new-reminder-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/leads-activities',
        'type' : 'POST',
        'data' : data,
        'cache' : false,
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },
        success: function(result){
            if(result.success === true)
            {
                let table = $('#reminder-list').DataTable();
                table.ajax.reload();

                $('#new-reminder').modal('toggle');

                toastr.success(result.message);
                $('#new-reminder-form').trigger('reset');
            }
            else if(result.success === false)
            {
                toastr.error(result.message);
            }

            $('.submit-form-btn').attr('disabled',false);
            $('.spinner').hide();

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('reminder_date','reminder_time','reminder_category','reminder_details');
});

$(document).on('change','#reminder_date',function(){
    let value = this.value;

    $.ajax({
        'url' : '/leads-activity-schedule',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'date' : value},
        beforeSend: function(){
            $('#new-reminder-form .display-schedule ul').remove();
            $('#new-reminder-form input, #new-reminder-form select, #new-reminder-form textarea, #new-reminder-form button').attr('disabled',true);
        },success: function (result) {
            $('#new-reminder-form .display-schedule').append('<ul></ul>');
            $.each(result, function(key, value){
                $('#new-reminder-form .display-schedule ul').append('<li><strong class="text-success">'+value.category+'</strong> <span class="text-primary">'+value.lead_id+'</span> - Time: <span class="text-muted">'+value.start_date+'</span></li>');
            });

            $('#new-reminder-form input, #new-reminder-form select, #new-reminder-form textarea, #new-reminder-form button').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('change','#edit_reminder_date',function(){
    let value = this.value;

    $.ajax({
        'url' : '/leads-activity-schedule',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'date' : value},
        beforeSend: function(){
            $('#edit-reminder-form .display-schedule ul').remove();
            $('#edit-reminder-form input, #edit-reminder-form select, #edit-reminder-form textarea, #edit-reminder-form button').attr('disabled',true);
        },success: function (result) {
            $('#edit-reminder-form .display-schedule').append('<ul></ul>');
            $.each(result, function(key, value){
                $('#edit-reminder-form .display-schedule ul').append('<li><strong class="text-success">'+value.category+'</strong> <span class="text-primary">'+value.lead_id+'</span> - Time: <span class="text-muted">'+value.start_date+'</span></li>');
            });

            $('#edit-reminder-form input, #edit-reminder-form select, #edit-reminder-form textarea, #edit-reminder-form button').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('click','.edit-reminder-btn',function(){
    let id = this.id;

    $.ajax({
        'url' :'/leads-activity/'+id+'/edit',
        'type' : 'GET',
        beforeSend:function(){
            $('#edit-reminder-form input,#edit-reminder-form select, #edit-reminder-form textarea,#edit-reminder-form button').attr('disabled',true);
        },success: function(result){
            $('#reminderId').val(id);
            $('#edit_reminder_date').val(result.date_scheduled);
            $('#edit_reminder_time').val(result.activity.start_date);
            $('#edit_reminder_category').val(result.activity.category).change();
            $('#edit_reminder_details').val(result.activity.details);

            $('#edit-reminder-form input,#edit-reminder-form select, #edit-reminder-form textarea,#edit-reminder-form button').removeAttr('disabled');
        },error: function(xhr, status, error){
        console.log(xhr);
    }
    });
});

$(document).on('click','.delete-reminder-btn',function(){
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
                'url' : '/leads-activity/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        let table = $('#reminder-list').DataTable();
                        table.ajax.reload();

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

$(document).on('submit','#edit-reminder-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray(),
    id = data[3].value;

    $.ajax({
        'url' : '/leads-activity/'+id,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },success: function(result){

            console.log(result);
            if(result.success === true)
            {
                let table = $('#reminder-list').DataTable();
                table.ajax.reload();

                toastr.success(result.message);
                $('#edit-reminder').modal('toggle');
            }else{
                toastr.error(result.message);
            }

            $('.submit-form-btn').attr('disabled',false);
            $('.spinner').hide();
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('click','.delete-timeline',function(){
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
                'url' : '/logs/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        $('.logs-'+id).remove();
                        Swal.fire(
                            'Deleted!',
                            'Activity logs has been deleted.',
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

$(document).on('submit','#website-link-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/website-link',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('#website-link-form .submit-form-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            if(result.success === true)
            {
                let item = '<li class="nav-item" id="link-'+result.websiteLink.id+'">' +
                    '<a class="nav-link">' +
                    '<a href="'+result.websiteLink.website_url+'" target="_blank" title="Click the link">'+result.websiteLink.website_name+'</a>' +
                    '<span class="float-right text-danger">' +
                    '<button type="button" class="btn btn-xs remove-link" id="'+result.websiteLink.id+'" title="Remove link"><i class="fa fa-times-circle"></i></button></span>' +
                    '</a></li>';

                $('.url-links').append(item);

                $('#website-link-form').trigger('reset');
                toastr.success(result.message);
                $('#social-links').modal('toggle')
            }else if(result.success === false){
                toastr.error(result.message);
            }

            $('#website-link-form .submit-form-btn').val('Save').attr('disabled',false);

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('website_name','url')
});

$(document).on('click','.remove-link',function(){
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
                'url' : '/website-link/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        $('#link-'+id).remove();
                        Swal.fire(
                            'Deleted!',
                            output.message,
                            'success'
                        );
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

$(document).on('click','.edit-timeline',function () {
   let id = this.id;
   $('#log_id').val(id);

   $.ajax({
       'url' : '/logs/'+id,
       'type' : 'GET',
       beforeSend: function () {
           let loader = '<div class="loading" align="center">' +
               '<strong>Loading</strong>' +
               '<div class="spinner-grow spinner-grow-sm text-info"></div>' +
               '<div class="spinner-grow spinner-grow-sm text-info"></div>' +
               '<div class="spinner-grow spinner-grow-sm text-info"></div>' +
               '</div>'

           $('#edit-log-touches-form .modal-body').prepend(loader);
           $('#edit-log-touches-form input, #edit-log-touches-form select, #edit-log-touches-form textarea, #edit-log-touches-form button').attr('disabled',true);
       },success: function(result){
           $('#edit-log-touches-form .loading').remove();
           if(result.success === true)
           {
               $('#edit_medium').val(result.logs.medium).change();
               $('#edit_date').val(result.timelineDate);
               $('#edit_time').val(result.logs.time);
               $('#edit_resolution').val(result.logs.resolution).change();
               $('#edit_description').val(result.logs.description);
               $('#edit-log-touches-form input, #edit-log-touches-form select, #edit-log-touches-form textarea, #edit-log-touches-form button').removeAttr('disabled');
           }else{
               setTimeout(function(){
                   toastr.error(result.message);
                   setTimeout(function(){
                       location.reload();
                   },3000);
               });
           }
       },error: function(xhr, status, error){
           console.log(xhr);
       }
   });
});

$(document).on('submit','#edit-log-touches-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray(), url = data[2].value;
    $.ajax({
        'url' : '/logs/'+data[4].value,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){

        },success: function(result){
            console.log(result);
            if(result.success === true)
            {
                $('#edit-log-touches .close').click();
                $('.timeline').load(url+' .timeline');
                toastr.success(result.message);
            }else{
                toastr.error(result.message);
                if(result.reload === true){
                    setTimeout(function(){
                        location.reload();
                    },3000);
                }
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('edit_medium','edit_date','edit_time','edit_resolution','edit_description');
});
///
