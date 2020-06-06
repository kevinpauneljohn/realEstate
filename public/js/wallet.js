$(document).on('click','.select-source',function(){
    $('#source-form .modal-body table').remove();
    let ctr = 0,valArray = [],inputCheck = 0;
    $('.source').each(function () {
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
        $.ajax({
            'url' : '/get-source',
            'type' : 'POST',
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            'data' : {'id':valArray},
            beforeSend: function(){

                $('#source-form .modal-body').html('<div class="load-template" style="margin: auto;width: 10%;margin-top:20px;">' +
                    '<div class="spinner-border text-primary" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>');
            },success: function(result){
                // console.log(result);
                $('#source-form .modal-body').html('<table class="table table-hover">' +
                    '<thead><tr><th>Current Amount</th><th>Description</th><th>Category</th>' +
                    '<th>Custom Amount</th>' +
                    '</tr></thead><tbody></tbody>' +
                    '</table>');
                $.each(result,function(key, value){
                    let field = '<tr>' +
                        '<td><span class="text-success">&#8369; '+value.amount+'</span></td>' +
                        '<td>'+value.details.description+'</td>' +
                        '<td><span class="text-primary">'+value.category+'</span></td>' +
                        '<td id="row-'+value.id+'"><input type="hidden" name="id[]" value="'+value.id+'"><input type="number" name="custom_amount[]" class="form-control" step="0.1" max="'+value.amount+'"></td>' +
                        '</tr>';
                    $('#source-form .modal-body table tbody').append(field);
                });

                $('.load-template').remove();
            },error: function(xhr, status, error){
                console.log(xhr);
            }
        });
    }
});

$(document).on('submit','#source-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();
    console.log(data);

    $.ajax({
        'url' : '/withdraw',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.text-danger').remove();
            $('.submit-withdraw-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){

            if(result.success === true)
            {
                let table = $('#wallet-list').DataTable();
                table.ajax.reload();
                toastr.success(result.message);
                $('#source-form .modal-body table').remove();
                $('#withdraw-money-modal').modal('toggle');

            }

            $.each(result, function(key, value){
                $('#row-'+key+' input[type="number"]').after('<p class="text-danger">'+value+'</p>');
            });

            $('.submit-withdraw-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr)
        }
    });
});

$(document).on('click','.money-history',function(){
    let id = this.id;

    console.log(id);
});
