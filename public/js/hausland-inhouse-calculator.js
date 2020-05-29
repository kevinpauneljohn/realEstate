$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

$(document).on('click','.equity-amount-btn',function () {
    let tcp = parseFloat($('#total_contract_price').val()),
        int_rate = parseFloat($('#equity_percentage').val());


    $('#exact_equity_amount').val(convert_rate(int_rate)*tcp);
});

$(document).on('click','.loanable-amount-btn',function () {
    let tcp = parseFloat($('#total_contract_price').val()),
        equity_amount = parseFloat($('#exact_equity_amount').val());


    $('#loanable_amount').val(tcp-equity_amount);
});

$(document).on('submit','.bank-computation-form',function(form){
    form.preventDefault();

    let formatter = new Intl.NumberFormat('en-PH', {
        minimumFractionDigits: 2, /* this might not be necessary */
    });

    let data = $(this).serializeArray(),
        total_contract_price = data[0].value;

    let processing_fee = parseFloat($('.bank-computation-form #processing_fee').val()),
        reservation_fee = parseFloat($('.bank-computation-form #reservation_fee').val()),
        pf_months = parseInt($('.bank-computation-form #pf_months').val()),
        equity = parseFloat($('.bank-computation-form #exact_equity_amount').val()),
        equity_months = parseInt($('.bank-computation-form #equity_months').val());

    let pf_less_rf = processing_fee - reservation_fee,
        pf_less_rf_amort = pf_less_rf/pf_months;
    pf_less_rf_amort = formatter.format(pf_less_rf_amort.toFixed(2));

    let equity_amort = equity / equity_months;
    equity_amort = formatter.format(equity_amort.toFixed(2));

    let loanable_amount = parseFloat($('#loanable_amount').val()),
        years = parseInt($('#years').val());



    let combined_months = "";
    if(pf_months > equity_months){
        combined_months = equity_months;
    }else if(pf_months === equity_months)
    {
        combined_months = pf_months;
    }else if(equity_months > pf_months){
        combined_months = pf_months;
    }

    let combined_equity_and_pf = (pf_less_rf/pf_months) + (equity / equity_months),
        balance = "",balance2 = "", balance3 = "",result = "";
    combined_equity_and_pf = formatter.format(combined_equity_and_pf.toFixed(2));

   if(years === 5)
   {
        balance = (total_contract_price-equity)*0.022244448;
        result = '<strong>5 yrs (12%)</strong> = '+numberWithCommas(formatter.format(balance.toFixed(2)))+'<br/>';
   }else if(years === 10)
   {
       balance = (total_contract_price-equity)*0.014347095;
       balance2 = (balance*0.0460251)+balance;
       result = '<strong>1-5 yrs (12%)</strong> = '+numberWithCommas(formatter.format(balance.toFixed(2)))+'<br/>' +
           '<strong>6-10 yrs (14%)</strong> = '+numberWithCommas(formatter.format(balance2.toFixed(2)))+'<br/>';
   }else if(years === 15)
   {
       balance = (total_contract_price-equity)*0.012001681;
       balance2 = (balance*0.0822151)+balance;
       balance3 = (balance2*0.0451175)+balance2;
       result = '' +
           '<strong>1-5 yrs (12%)</strong> = '+numberWithCommas(formatter.format(balance.toFixed(2)))+'<br/>' +
           '<strong>6-10 yrs (14%)</strong> = '+numberWithCommas(formatter.format(balance2.toFixed(2)))+'<br/>'+
           '<strong>11-15 yrs (16%)</strong> = '+numberWithCommas(formatter.format(balance3.toFixed(2)))+'<br/>';
   }

    $('.calculation-result').html(function(){
        let content = '<div class="callout callout-info" style="margin-top:10px;">' +
            '<strong>Total Contract Price</strong> : '+numberWithCommas(total_contract_price)+'<br/><br/>'+
            '<strong>(PF) Processing fee</strong> : '+numberWithCommas(processing_fee)+'<br/>'+
            '<strong>(RF) Reservation fee</strong> : '+numberWithCommas(reservation_fee)+'<br/>'+
            '<strong>PF less RF</strong> : '+numberWithCommas(pf_less_rf)+'<br/>'+
            '<strong>PF less RF within '+pf_months+' months</strong> : '+numberWithCommas(pf_less_rf_amort)+'<br/><br/>'+
            '<strong>Down Payment</strong> : '+numberWithCommas(equity)+'<br/>'+
            '<strong>Down Payment within '+equity_months+' month/s</strong> : '+numberWithCommas(equity_amort)+'<br/><br/>'+
            '<strong>Combined DP and PF within '+combined_months+' month/s</strong> : '+numberWithCommas(combined_equity_and_pf)+'<br/><br/>'+
            '<strong>Loanable Amount</strong> : '+numberWithCommas(loanable_amount)+'<br/><br/>'+
            '<p><strong>Monthly Amortization For The Chosen Loan Tenure</strong><br/>' +result+
            '</p></div>';

        return content;
    })
});

$(document).on('click','.bank-calculator-reset',function(){
    $('.text-danger').remove();
    $('.calculation-result').html("");
});

function convert_rate(rate)
{
    return rate/100;
}

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}
