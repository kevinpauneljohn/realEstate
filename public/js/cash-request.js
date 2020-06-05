$(document).on('submit','.cash-request-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray(),
        id = data[1].value;

    $.ajax({
        'url' : '/cash-approval-result',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('#error-'+id).remove();
        },success: function (result) {
            console.log(result);
            if(result.success === false)
            {
                $('#'+result.error).after('<p class="text-danger" id="error-'+id+'">This is a required field</p>');
            }
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
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
