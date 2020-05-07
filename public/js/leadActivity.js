function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

function submitform(url , type , data , message , reload = true, elementAttr, consoleLog = true)
{
    $.ajax({
        'url' : url,
        'type' : type,
        'data' : data,
        'cache' : false,
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },
        success: function(result, status, xhr){
            if(consoleLog === true)
            {
                console.log(result);
            }
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success(message)
                    setTimeout(function(){
                        if(reload === true)
                        {
                            location.reload();
                        }
                    },1500);
                });
            }else{
                $('.submit-form-btn').attr('disabled',false);
                $('.spinner').hide();
            }

            $.each(result, function (key, value) {
                var element = $(elementAttr+'#'+key);

                element.closest(elementAttr+'div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
}

$(document).ready(function () {

    $('#add-schedule-form').submit(function (form) {
        form.preventDefault();

        let data = $('#add-schedule-form').serialize();

        submitform(
            '/leads-activities',
            'POST',
            data,
            'New Schedule Successfully Added!',
            true,
            '',
            false,
        );
        clear_errors('schedule','category');
    });

    $('#edit-schedule-form').submit(function (form) {
        form.preventDefault();

        let data = $('#edit-schedule-form').serialize();
        let id = $('#scheduleId').val();

        submitform(
            '/leads-activity/'+id,
            'PUT',
            data,
            'Schedule Successfully Updated!',
            true,
            '',
            false,
        );
        clear_errors('edit_schedule','edit_category');
    });

    $('#delete-schedule-form').submit(function (form) {
        form.preventDefault();

        let data = $('#delete-schedule-form').serialize();
        let id = $('#deleteScheduleId').val();

        submitform(
            '/leads-activity/'+id,
            'DELETE',
            data,
            'Schedule Successfully Deleted!',
            true,
            '',
            false,
        );
    });
});

$(document).on('change','#schedule',function () {
    let date = $('#schedule').val();

    $('#schedules').html("");
    $.ajax({
        'url' : '/leads-schedule/'+date,
        'type' : 'GET',
        beforeSend: function(){
            $('#schedules').html("");
        },
        success: function (result) {
            console.log(result.length);
            if(result.length === 0)
            {
                $('#schedules').html("<h3>Nothing To Show</h3>");
            }else{
                $('#schedules').html('<div class="timeline timeline-inverse"></div>');
                $.each(result, function (key, value) {
                    var details = "";
                    if(value.details !== null)
                    {
                        details = value.details;
                    }
                    $('.timeline').append(
                        '<div class="time-label"><span class="bg-primary"> '+value.schedule+'</span></div>' +
                        '<div>' +
                        '<i class="fas fa-calendar-alt bg-primary"></i>' +
                        '<div class="timeline-item">' +
                        '<span class="time"><i class="far fa-clock"></i> '+value.start_date+'</span>' +
                        '<h3 class="timeline-header"><a href="#">'+value.category+'</a></h3>' +
                        '<div class="timeline-body">' +
                        ''+details+'<br/><a href="/leads/'+value.lead_id+'">View Details</a></div>' +
                        '</div>' +
                        '</div>');
                });
            }
        }
    });
});

$(document).on('change','#edit_schedule',function () {
    let date = $('#edit_schedule').val();

    $.ajax({
        'url' : '/leads-schedule/'+date,
        'type' : 'GET',
        beforeSend: function(){
            $('#edit_schedules').html("");
        },
        success: function (result) {
            if(result.length === 0)
            {
                $('#edit_schedules').html("<h3>Nothing To Show</h3>");
            }else{
                $('#edit_schedules').html('<div class="timeline timeline-inverse"></div>')
                $.each(result, function (key, value) {
                    var details = "";
                    if(value.details !== null)
                    {
                        details = value.details;
                    }
                    $('.timeline').append(
                        '<div class="time-label"><span class="bg-primary"> '+value.schedule+'</span></div>' +
                        '<div>' +
                        '<i class="fas fa-calendar-alt bg-primary"></i>' +
                        '<div class="timeline-item">' +
                        '<span class="time"><i class="far fa-clock"></i> '+value.start_date+'</span>' +
                        '<h3 class="timeline-header"><a href="#">'+value.category+'</a></h3>' +
                        '<div class="timeline-body">' +
                        ''+details+'<br/><a href="/leads/'+value.lead_id+'">View Details</a></div>' +
                        '</div>' +
                        '</div>');
                });
            }
        }
    });
});

$(document).on('click','.edit-schedule-btn',function (schedule) {
    let id = this.id;

    $.ajax({
        'url' : '/leads-activity/'+id+'/edit',
        'type' : 'GET',
        success: function (result) {
            $('#scheduleId').val(result.id);
            $('#edit_schedule').val(result.schedule);
            $('input[name=edit_start_time]').val(result.start_date);
            $('input[name=edit_end_time]').val(result.end_date);
            $('#edit_remarks').summernote("code", result.details);
            $('#edit_category').val(result.category).change();
        }
    });
});

$(document).on('click','.delete-schedule-btn',function () {
    let id = this.id;
    $('#deleteScheduleId').val(id);
});


$(document).on('submit','#log-touches-form',function(form){
    form.preventDefault();
    let data = $('#log-touches-form').serializeArray();

    submitform('/logs','POST',data,'Activity Logs successfully created!',true,'',false);
    clear_errors('medium','date','time','resolution');
});
