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


$(document).on('click','.mark-all',function(){
    $('.notify-un-viewed').not(this).prop('checked', this.checked);
});

$(document).on('change','.select-action',function(){
    let action = this.value;

    if(action === 'Mark as read')
    {
        let ctr = 0,valArray = [],inputCheck = 0;
        $('.mark-box').each(function () {
            let value = (this.checked ? $(this).val() : "");

            if(value !== "")
            {
                valArray[ctr] = value;
                ctr++;
                inputCheck++;
            }
        });

        if(inputCheck > 0)
        {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Mark as read!'
            }).then((result) => {
                if (result.value) {
                      $.ajax({
                        'url' : '/notifications-bulk',
                        'type' : 'PUT',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        'data' : {'_method':'PUT','id' : valArray},
                        beforeSend: function(){

                        },success: function(output){
                            console.log(output);
                            if(output.success === true){
                                Swal.fire(
                                    'Mark as read!',
                                    output.message,
                                    'success'
                                );

                                let table = $('#notifications-list').DataTable();
                                table.ajax.reload();
                            }else{
                                toastr.error(output.message);
                            }
                        },error: function(xhr, status, error){
                            console.log(xhr);
                        }
                    });

                }
            });
        }
    }
});
