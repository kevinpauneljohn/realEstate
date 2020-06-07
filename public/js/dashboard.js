$(document).on('change','.display-period',function(){
    let value = this.value;

    $.ajax({
        'url' : '/set-lead-graph-display',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'status':value},
        beforeSend: function(){
            $('.display-period').attr('disabled',true);
        },success: function(result){
            console.log(result);
            if(result.success === true)
            {
                let url = window.location.href;
                location.reload();
            }
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});
