// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('c7e08e74c7aad7fb625e', {
    cluster: 'ap1'
});

var channel = pusher.subscribe('notification-channel');
channel.bind('notification', function(result) {
    let url = window.location.href;
    $('.reminder-notification').load(url+' .reminder-notification');
    responsiveVoice.speak("hello world", "UK English Male");
});

