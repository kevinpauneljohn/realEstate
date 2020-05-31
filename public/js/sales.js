function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

function submitform(url , type , data , reload = true, elementAttr, consoleLog = true, errorMessage)
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
                    toastr.success(result.message);
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
            setTimeout(function(){
                toastr.error(errorMessage);
                setTimeout(function(){
                  //location.reload();
                },1500);
            });
        }
    });
}

// $(document).ready(function () {
//
//     $('#add-sales-form').submit(function (form) {
//         form.preventDefault();
//
//         let data = $(this).serializeArray();
//         submitform(
//             '/sales',
//             'POST',
//             data,
//             false,
//             '',
//             true,
//             'You do not have commission rate yes <strong>Please approach your up line regarding this</strong>'
//         );
//         //clear_errors('reservation_date','buyer','project','model_unit','total_contract_price','financing');
//     })
// });

$(document).on('submit','#add-sales-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/sales',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){

        },success: function(result){
            console.log(result);

            if(result.success === true)
            {
                $('#model_unit').html('<option value=""></option>');
                let item = '<div class="callout callout-success">' +
                    '<h5>Sales successfully added!</h5>' +
                    '<p><a href="#">View Sales Details here</a></p></div>';
                $('#example1_wrapper').prepend(item);
                $('#add-sales-form').trigger('reset');
                toastr.success(result.message);
            }else if(result.success === false)
            {
                toastr.error(result.message);
            }

            $('.submit-form-btn').attr('disabled',false);
            $('.spinner').hide();

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('reservation_date','buyer','project','model_unit',
        'total_contract_price','discount','processing_fee','reservation_fee',
        'equity','loanable_amount','financing');
});

function projectChange(paramId, element)
{
    $.ajax({
        'url' : '/project-model-units/'+paramId,
        'type' : 'GET',
        beforeSend: function(){
            $('#'+element).html("");
        },
        success: function(result){
            $.each(result, function (key, value) {
                //console.log(value.name);
                $('#'+element).append('<option value="'+value.id+'">'+value.name+'</option>');
            });
        }
    });
}
$(document).on('change','#project',function(){
    let value = this.value;
    projectChange(value, 'model_unit');
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

/*change sale status*/

let status, //instantiate the current sale status
    id;

$(document).on('click','.update-sale-status-btn',function () {
    id = this.id; /*get the id value*/
    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    status = data[8].toLowerCase();

    $('#updateSaleId').val(id);
    $('#status').val(status).change();
});

$(document).on('change','#status',function () {
    let value = this.value,
        updateStatusBtn = $('#status-submit-btn'),
        disabled = true;

    if(value !== status && value !== '')
    {
        disabled = false;
    }
    updateStatusBtn.attr('disabled',disabled)
});

$(document).on('submit','#edit-status-form',function (form) {
    form.preventDefault();

    submitform(
        '/sale-status-update',
        'PUT',
        $('#edit-status-form').serialize(),
        true,'',true,''
    );
});


$(document).on('click','.edit-sales-btn',function () {
    id = this.id;
    $.ajax({
        'url' : '/sales/edit/'+id,
        'type' : 'GET',
        beforeSend: function () {
            $('input, select, textarea').attr('disabled',true);
        },success: function (result) {
            console.log(result);
            $('#updateSalesId').val(id);
            $('#edit_reservation_date').val(result.reservation_date).attr('value',result.reservation_date).each(function(){
                $(this).datepicker('setDate', $(this).val());
            });
            $('#edit_buyer').val(result.lead_id).change();
            $('#edit_project').val(result.project_id).change();
            $('#edit_lot_area').val(result.lot_area);
            $('#edit_floor_area').val(result.floor_area);
            $('#edit_phase').val(result.phase);
            $('#edit_block_number').val(result.block);
            $('#edit_lot_number').val(result.lot);
            $('#edit_total_contract_price').val(result.total_contract_price);
            $('#edit_discount').val(result.discount);
            $('#edit_processing_fee').val(result.processing_fee);
            $('#edit_reservation_fee').val(result.reservation_fee);
            $('#edit_equity').val(result.equity);
            $('#edit_loanable_amount').val(result.loanable_amount);
            $('#edit_financing').val(result.financing).change();
            $('#edit_dp_terms').val(result.terms);
            $('#edit_details').summernote("code", result.details);

            $.each(result.modelUnit, function (key, value) {
                $('#edit_model_unit').append('<option value="'+value.id+'">'+value.name+'</option>');
            });

            $('#edit_model_unit').val(result.model_unit_id).change();

            $('input, select, textarea').attr('disabled',false);
        },error: function (xhr, status, error) {
            console.log(xhr);
        }
    });
});

$(document).on('change','#edit_project',function(){
    let value = this.value;
    projectChange(value, 'edit_model_unit');
});

$(document).on('submit','#edit-sales-form',function (form) {
    form.preventDefault();

    let data = $('#edit-sales-form').serializeArray();

    // submitform('/sales/'+id, 'PUT',data,
    //     true,'',true,'');

    $.ajax({
        'url'   : '/sales/'+id,
        'type'  : 'PUT',
        'data'  : data,
        beforeSend: function(){
            $('.submit-form-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){
            console.log(result);
            if(result.success === true)
            {
                var table = $('#sales-list').DataTable();
                table.ajax.reload();

                $('#edit-sales-form').trigger('reset');
                $('#edit-sales-modal').modal('toggle');
                toastr.success(result.message);
            }else if(result.success === false){
                toastr.error(result.message);
            }
            $.each(result, function (key, value) {
                var element = $('#'+key);

                element.closest('div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

            $('.submit-form-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('edit_reservation_date','update_reason','edit_project',
        'edit_model_unit','edit_total_contract_price','edit_financing','update_reason');
});

$(document).on('click','.view-request-btn',function () {
    id = this.id;
    $.ajax({
        'url' : '/requests/number',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'id':id},
        beforeSend: function(){
            $('.tickets').remove();
            $('#view-request .modal-body').prepend('<div align="center" class="loader"><div class="spinner-border"></div></div>');
        },
        success: function (result) {
            let ctr = 1;
            let label="";

            $('#view-request .modal-body .loader').remove();
            $.each(result, function (key, value) {
                if(value.status === 'pending')
                {
                    label = '<span class="badge bg-cyan">'+value.status+'</span>';
                }
                else if(value.status === 'approved')
                {
                    label = '<span class="badge bg-success">'+value.status+'</span>';
                }
                else if(value.status === 'rejected')
                {
                    label = '<span class="badge bg-danger">'+value.status+'</span>';
                }
                $('.request-ticket').append('<tr class="tickets"><td>'+ctr+'</td><td><a href="/requests/'+value.id+'" target="_blank" title="Click here">'+String(value.id).padStart(5, '0')+'</a></td><td>'+label+'</td></tr>');
                ctr++;
            })

        },error: function (xhr, status, error) {
            console.log(xhr);
            // $.each(xhr, function (key, value) {
            //     console.log('Key: '+key+' Value: '+value);
            // });
            // console.log('Status: '+status+' Error: '+error);
        }
    });
});



