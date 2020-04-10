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
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },
        success: function(result, status, xhr){
            if(consoleLog === true)
            {
                console.log(result);
            }
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success(message);
                    setTimeout(function(){
                        if(reload === true)
                        {
                            location.reload();
                        }
                    },1500);
                });
            }
            else{
                if(result.success === false)
                {
                    toastr.error(result.message);
                }

                $('.submit-form-btn').attr('disabled',false);
                $('.spinner').hide();
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
            toastr.error("You don't have commission rate yet.");
        }
    });
}

$(document).ready(function () {

    $('#add-sales-form').submit(function (form) {
        form.preventDefault();

        let data = $('#add-sales-form').serialize();
        submitform(
            '/sales',
            'POST',
            data,
            'New sales Successfully Added!',
            true,
            '',
            false,
        );
        clear_errors('reservation_date','buyer','project','model_unit','total_contract_price','financing');
    })
});

$(document).on('change','#project',function(){
    let value = this.value;
    $('#model_unit').html('<option value=""> -- Select -- </option>');
    $.ajax({
        'url' : '/project-model-units/'+value,
        'type' : 'GET',
        success: function(result){
            $.each(result, function (key, value) {
                //console.log(value.name);

                $('#model_unit').append('<option value="'+value.id+'">'+value.name+'</option>');
            });
        }
    });
});

function statusLabel(status)
{
    if(status == 'reserved')
    {
        return '<span class="badge badge-info right role-badge">Reserved</span>';
    }else if(status == 'cancelled')
    {
        return '<span class="badge badge-danger right role-badge">Cancelled</span>';
    }else if(status == 'paid')
    {
        return '<span class="badge badge-success right role-badge">Paid</span>';
    }
}

$(document).on('click','.view-sales-btn',function(){
    let id = this.id;

    $('#salesId').val(id);
    $.ajax({
        'url' : '/sales/'+id,
        'type' : 'GET',
        beforeSend: function (request, settings) {
            $('.image-loader').show();
            $('.sales-details').hide();
        },
        success: function(result){
            // console.log(result);
            $('.image-loader').hide();
            $('.sales-details').show();

            let tcp = parseInt(result.sales.total_contract_price);
            let discountAmount = parseInt(result.sales.discount);
            let pf = parseInt(result.sales.processing_fee);
            let rf = parseInt(result.sales.reservation_fee);
            let equity = parseInt(result.sales.equity);
            let loan_amount = parseInt(result.sales.loanable_amount);
            let email = "", contactNumber = "", phase ="", block ="",lot ="", lot_area = "", floor_area = "", equity_terms = "";

            if(result.leads.email != null)
            {
                email = result.leads.email;
            }
            if(result.leads.mobileNo != null)
            {
                contactNumber = result.leads.mobileNo;
            }
            if(result.sales.phase != null)
            {
                phase = result.sales.phase;
            }
            if(result.sales.block != null)
            {
                block = result.sales.block;
            }
            if(result.sales.lot != null)
            {
                lot = result.sales.lot;
            }
            if(result.model_unit.lot_area != null)
            {
                lot_area = result.model_unit.lot_area;
            }
            if(result.model_unit.floor_area != null)
            {
                floor_area = result.model_unit.floor_area;
            }
            if(result.sales.terms != null)
            {
                equity_terms = result.sales.terms;
            }

            $('#sale-status').html('<strong>'+statusLabel(result.sales.status)+'</strong>');
            $('#reservation-date').html('<strong>'+result.sales.reservation_date+'</strong>');
            $('#buyer-name').html('<strong>'+result.leads.firstname+' '+result.leads.lastname+'</strong>');
            $('#contact-number').html('<strong>'+contactNumber+'</strong>');
            $('#email-address').html('<strong>'+email+'</strong>');
            $('#commission-rate').html('<strong>'+result.sales.commission_rate+'%</strong>');
            $('#project-name').html('<strong>'+result.project.name+'</strong>');
            $('#model-unit-name').html('<strong>'+result.model_unit.name+'</strong>');
            $('#lot-area').html('<strong>'+lot_area+'</strong>');
            $('#floor-area').html('<strong>'+floor_area+'</strong>');
            $('#location').html('<strong>Phase: '+phase+' Block:'+block+' Lot:'+lot+'</strong>');
            $('#total-contract-price').html('<strong>&#8369; '+tcp.toLocaleString()+'</strong>');
            $('#discount-amount').html('<strong>&#8369; '+discountAmount.toLocaleString()+'</strong>');
            $('#processing-fee').html('<strong>&#8369; '+pf.toLocaleString()+'</strong>');
            $('#reservation-fee').html('<strong>&#8369; '+rf.toLocaleString()+'</strong>');
            $('#equity-amount').html('<strong>&#8369; '+equity.toLocaleString()+'</strong>');
            $('#loanable-amount').html('<strong>&#8369; '+loan_amount.toLocaleString()+'</strong>');
            $('#financing-terms').html('<strong>'+result.sales.financing+'</strong>');
            $('#dp-terms').html('<strong>'+equity_terms+'</strong>');

            /*requirements tab*/

                // $.each(result.requirements, function (key, value){
                //     console.log(value[0].description);
                // });

        }
    });
});

$(document).on('change','#model_unit',function(){
    let value = this.value;

    $.ajax({
        'url' : '/get-model-unit-details/'+value,
        'type' : 'GET',
        success: function(result)
        {
            $('#lot_area').val(result.lot_area);
            $('#floor_area').val(result.floor_area);
        }
    });
});

