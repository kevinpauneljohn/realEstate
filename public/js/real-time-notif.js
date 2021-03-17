// // Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

var pusher = new Pusher('c7e08e74c7aad7fb625e', {
    cluster: 'ap1'
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function(data) {
    let read;
    if(data.message.action === "task updated")
    {
        read = 'Task number '+parseInt(data.message.ticket)+' assigned to '+data.message.assigned+' was updated';
    }else if(data.message.action === "task created")
    {
        read = "A new task number "+parseInt(data.message.ticket)+" was assigned to "+data.message.assigned;
    }
    else if(data.message.action === "task agent updated")
    {
        read = "task number "+parseInt(data.message.ticket)+" was assigned to "+data.message.assigned;
    }

    responsiveVoice.speak(read);

    $('.main-section .task-action-button').load(window.location.href+' .task-action-button');

    $('.reminder-notification').find('.my-task-notification').load(window.location.href+' .my-task-notification');
    let table = $('#task-list').DataTable();
    table.ajax.reload();
});
