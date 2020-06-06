$(document).on('submit','.cash-request-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray(),
        id = data[1].value;

    if(data[4].value !== "")
    {
        $('#error-'+id).remove();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, '+data[4].value+' it!'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    'url' : '/cash-approval-result',
                    'type' : 'POST',
                    'data' : data,
                    beforeSend: function(){
                        $('#error-'+id).remove();
                        $('#cash-request-btn-'+id).val('Submitting ... ').attr('disabled',true);
                    },success: function (output) {
                        console.log(output);
                        if(output.success === false)
                        {
                            $('#'+output.error).after('<p class="text-danger" id="error-'+id+'">This is a required field</p>');
                        }else if(output.success === true)
                        {
                            let url = window.location.href;
                            $('.card').load(url+' #card-'+id);
                            Swal.fire(
                                'Submitted!',
                                output.message,
                                'success'
                            );
                        }

                        $('#cash-request-btn-'+id).val('Submit').attr('disabled',false);
                    },error: function(xhr, status, error){
                        console.log(xhr);
                    }
                });
            }
        });
    }else{
        $('#action-'+id).after('<p class="text-danger" id="error-'+id+'">This is a required field</p>');
    }
});

$(document).on('click','.plus',function(){

    let id = $(this).closest('.table-row').find('.extra_field_id').val();


    let item = '<tr class="extra-row-'+id+' table-row">' +
        '<td width="20%"><input type="text" class="form-control" name="extra_amount[]"></td>' +
        '<td width="68%"><input type="text" class="form-control" name="extra_description[]"></td>' +
        '<td>' +
        '<input type="hidden" class="extra_field_id" value="'+id+'">' +
        '<button type="button" class="btn btn-danger btn-sm float-right minus" style="margin:2px;"><i class="fa fa-minus"></i></button>' +
        '<button type="button" class="btn btn-success btn-sm float-right plus" style="margin:2px;"><i class="fa fa-plus"></i></button>' +
        '</td>' +
        '</tr>';
    $('#table-'+id+' tbody').append(item);
});

$(document).on('click','.minus',function(){
    let id = $(this).closest('.table-row').find('.extra_field_id').val();

    if($('.extra-row-'+id).length > 1)
    {
        $(this).closest('.extra-row-'+id).remove();
    }
});
