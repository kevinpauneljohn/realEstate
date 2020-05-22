let approve = $('#approve-form'),
    reject = $('#reject-form');

$(document).on('submit','#approve-form',function(form){
    form.preventDefault();
    let data = approve.serializeArray();

    submitform('/requests/'+data[2]['value'],'PUT',data,true,'',true,'');
});

$(document).on('submit','#reject-form', function (form) {
    form.preventDefault();

    let data = reject.serializeArray();
    submitform('/requests/'+data[2]['value'],'PUT',data,true,'#reject-form ',true,'');
});

$(document).on('change','#request-status',function(){
    let value = this.value;

    $.ajax({
        'url' : '/requests/status',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'status':value},
        success: function (result) {
            ///console.log(result);
            if(result.success === true)
            {
                var table = $('#threshold-list').DataTable();
                table.ajax.reload();
            }
            // location.reload();

        },error: function (xhr, status, error) {
            $.each(xhr, function (key, value) {
                console.log('Key: '+key+' Value: '+value);
            });
            console.log('Status: '+status+' Error: '+error);

            setTimeout(function(){
               location.reload();
            },100);
        }
    });
});

$(document).on('click','.unlock-btn',function () {
    id = this.id;

    $.ajax({
        'url' : '/requests/open',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'id':id},
        success: function (result) {
            if(result.success === true)
            {
                let table = $('#threshold-list').DataTable();
                table.ajax.reload();

                toastr.success(result.message);
            }
        },error: function (xhr, status, error) {
            $.each(xhr, function (key, value) {
                console.log('Key: '+key+' Value: '+value);
            });
            console.log('Status: '+status+' Error: '+error);

            setTimeout(function(){
                location.reload();
            },100);
        }
    });
});


