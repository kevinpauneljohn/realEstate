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
            'New Permission Successfully Added!',
            true,
            '',
            false,
        );
        clear_errors('schedule','category');
    });
});

$(document).on('change','#schedule',function () {
    let date = $('#schedule').val();

    $('#schedules').html("");
    $.ajax({
        'url' : '/leads-schedule/'+date,
        'type' : 'GET',
        success: function (result) {

            $.each(result, function (key, value) {
                $('#schedules').append('<div class="timeline timeline-inverse">' +
                    '<div class="time-label"><span class="bg-primary">'+value.schedule+'</span></div>' +
                    '<div>' +
                    '<i class="fas fa-calendar-alt bg-primary"></i>' +
                    '<div class="timeline-item">' +
                    '<span class="time"><i class="far fa-clock"></i>'+value.start_time+'</span>' +
                    '<h3 class="timeline-header"><a href="#">'+value.category+'</a></h3>' +
                    '<div class="timeline-body">'+value.details+'</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
            });
        }
    });
});


$(document).on('click','.edit-schedule-btn',function () {
    let id = this.id;

    $.ajax({
        'url' : '/leads-activity/'+id+'/edit',
        'type' : 'GET',
        success: function (result) {
            $('#schduleId').val(result.id);
            $('#edit_schedule').val(result.schedule);
            $('#edit_start_time').val(result.start_date);
            $('#edit_end_time').val(result.end_date);
            $('#edit_remarks').val(result.details);
            $('#edit_category').val(result.details).change();
        }
    });
});
