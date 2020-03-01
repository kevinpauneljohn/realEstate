$(document).on('change','.custom-control-input',function () {
    //console.log(this.value);

    $.ajax({
        'url': '/update-schedule-status',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'type' : 'POST',
        'data' : {'id' : this.value, 'status' : this.checked},
        success: function(result){
            console.log(result);
        }
    });
});
