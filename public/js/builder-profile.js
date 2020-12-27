let rowId;

function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}
$(document).on('submit','#builder-member-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/add-member/builder',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.member-btn').attr('disabled',true).val('Adding...');
        },
        success: function(result){
            console.log(result);
            if(result.success === true)
            {
                toastr.success(result.message);
                $('#builder-member-form select').val('').change();
                $('#member-list').DataTable().ajax.reload();
                $('#builder-member-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
            $('.member-btn').attr('disabled',false).val('Add');
        },error: function(xhr,status,error){
            if(xhr.responseJSON.message === 'CSRF token mismatch.')
            {
                location.reload();
            }
            console.log(xhr, status, error);
            $('.member-btn').attr('disabled',false).val('Add');
        }
    });
    clear_errors('members');
});

$(document).on('click','.delete-btn',function () {
    rowId = this.id;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                'url' : '/builder/'+rowId+'/member',
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE'},
                beforeSend: function(){

                },success: function(output){
                    //console.log(output);
                    if(output.success === true){
                        $('#member-list').DataTable().ajax.reload();
                        Swal.fire(
                            'Deleted!',
                            output.message,
                            'success'
                        );
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });

        }
    });
});
