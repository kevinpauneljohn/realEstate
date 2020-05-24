$(document).on('click','.mark-read',function(){
    let id = this.id;
    $.ajax({
        'url' : '/notifications/'+id,
        'type' : 'PUT',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function(){

        },success: function(result){
            if(result.success === true)
            {
                toastr.success(result.message);
                let table = $('#notifications-list').DataTable();
                table.ajax.reload();
            }
        },error: function(xhr, status, error){
        console.log(xhr);
    }
    });
});
