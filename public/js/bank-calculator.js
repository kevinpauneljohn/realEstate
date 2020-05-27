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

    console.log(data);

    let loanable_amount = parseFloat($('#loanable_amount').val()),
        interest_rate = convert_rate(parseFloat($('#interest_rate').val())),
        years = $('#years').val();

    let converted_rate = parseFloat(interest_rate/12),
        converted_years = parseInt(years*12);

    let numerator = (1+converted_rate);
        numerator = (Math.pow(numerator,converted_years))*converted_rate;

    let denominator = (1 + converted_rate);
    denominator = (Math.pow(denominator,converted_years))-1;

    let mortgage = (numerator/denominator)*loanable_amount;
    mortgage = formatter.format(mortgage.toFixed(2));

    console.log(mortgage);

    $('.calculation-result').html(function(){
        let content = '<div class="callout callout-info" style="margin-top:10px;">' +
            '<strong>Total Contract Price</strong> : '+numberWithCommas(data[0].value)+'<br/>'+
            '<strong>Equity</strong> : '+numberWithCommas(data[2].value)+'<br/>'+
            '<strong>Loanable Amount</strong> : '+numberWithCommas(data[3].value)+'<br/>'+
            '<p><strong>Estimated Monthly Amortization For The Chosen Loan Tenure at '+data[4].value+'%</strong><br/>' +
            '<strong>'+data[5].value+' years = </strong>'+numberWithCommas(mortgage)+'</p>'+
            '</div>';

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
