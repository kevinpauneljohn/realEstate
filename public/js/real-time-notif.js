// // Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

var pusher = new Pusher('c7e08e74c7aad7fb625e', {
    cluster: 'ap1'
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function(data) {
    responsiveVoice.speak("A new task number "+parseInt(data.message.ticket)+" was assigned to "+data.message.assigned);
    $('.reminder-notification').find('.my-task-notification').load(window.location.href+' .my-task-notification');
    let table = $('#task-list').DataTable();
    table.ajax.reload();
});
