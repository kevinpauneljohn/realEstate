function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

function submitform(url , type , data , message , reload = true, elementAttr, consoleLog = true)
{
    $.ajax({
        'url' : url,
        'type' : type,
        'data' : data,
        'cache' : false,
        success: function(result, status, xhr){
            if(consoleLog === true)
            {
                console.log(result);
            }
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success(message)
                    setTimeout(function(){
                        if(reload === true)
                        {
                            location.reload();
                        }
                    },1500);
                });
            }
            $.each(result, function (key, value) {
                var element = $(elementAttr+'#'+key);

                element.closest(elementAttr+'div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
}

$(document).ready(function(){


    /*delete user*/
    $('#delete-lead-form').submit(function(form){
        form.preventDefault();

        let id = $('#deleteLeadId').val();
        let data = $('#delete-lead-form').serialize();

        submitform(
            '/leads/'+id,
            'DELETE',
            data,
            'New Permission Successfully Deleted!',
            true,
            '',
            false,
        );
    });
});


/*delete trigger popup*/
$(document).on('click','.delete-lead-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();


    $('.delete-lead-name').html('<strong style="color:yellow;">'+data[1]+' '+data[2]+'</strong>?');
    $('.lead-details').html('<table>' +
        '<tr><td>Mobile No.</td><td>'+data[3]+'</td></tr>' +
        '<tr><td>Email</td><td>'+data[4]+'</td></tr>' +
        '<tr><td>Point Of Contact</td><td>'+data[5]+'</td></tr></table>');
    $('#deleteLeadId').val(id);
});


$(document).on('click','.view-details',function(){
    id = this.id;
    $.ajax({
        'url' : '/leads/get',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'id':id},
        beforeSend: function () {
            $('.image-loader').show();
            $('.lead-details-table').remove();
        },success: function(result){
            console.log(result);
            let mobile = result.mobileNo != null? result.mobileNo:"",
                landline = result.landline != null? result.landline:"",
                email = result.email != null? result.email:"",
                status = result.status != null? result.status:"",
                income_range = result.income_range != null? result.income_range:"";

            $('#lead-details .modal-body').append('<table class="table table-bordered table-hover lead-details-table">' +
                '<tr><td>Status</td><td>'+result.lead_status+'</td></tr>' +
                '<tr><td>Date Inquired</td><td>'+result.date_inquired+'</td></tr>' +
                '<tr><td>Full Name</td><td>'+result.id+'</td></tr>' +
                '<tr><td>Mobile Phone</td><td>'+mobile+'</td></tr>' +
                '<tr><td>Land line</td><td>'+landline+'</td></tr>' +
                '<tr><td>Email</td><td>'+email+'</td></tr>' +
                '<tr><td>Civil Status</td><td>'+status+'</td></tr>' +
                '<tr><td>Income Range</td><td>'+income_range+'</td></tr>' +
                '<tr><td>Project Interested</td><td>'+result.project+'</td></tr>' +
                '<tr><td colspan="2"><strong>Remarks</strong><p>'+result.remarks+'</p></td></tr></table>');

            $('.image-loader').hide();
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
        }
    });
});

let id = "", value = "", originalValue = "";
$(document).on('change','.change-status', function(){
    id = this.id, value = this.value;

    $.ajax({
        'url' : '/leads/status',
        'type' : 'POST',
        'data' : {'id':id},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function(){

        },
        success: function (result) {
            console.log(result);
            if(result !== "For follow-up" && value === "For follow-up")
            {
                $('#set-schedule').modal();
            }
        },error: function(xhr,status,error){
            console.log(xhr, status, error);
        }
    });
});


