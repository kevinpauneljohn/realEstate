$(document).on('submit','#apec-homes',function(form){
    form.preventDefault();

    $('.text-danger').remove();
    let data = $(this).serializeArray();

    let total_contract_price = data[0].value,
        discount = data[1].value,
        reservation_fee = data[2].value,
        loanable_amount = data[3].value,
        months_to_pay = data[4].value;

    let discounted_tcp = total_contract_price-discount,
        discounted_tcp_less_rf = discounted_tcp-reservation_fee,
        discounted_tcp_less_loanable = discounted_tcp_less_rf-loanable_amount,
        man_NEP = discounted_tcp_less_loanable/months_to_pay;

    let input_length = 0;
    $.each(data,function(key, value){
        if(value.value.length < 1)
        {
            $('#'+value.name).after('<p class="text-danger">This field is required</p>');
        }else{
            input_length = input_length + 1;
        }
    });

    if (input_length === 5){
        $('.calculation-result').html(function(){
            let content = '<div class="callout callout-info" style="margin-top:10px;">' +
                '<strong>Total Contract Price: </strong>'+numberWithCommas(total_contract_price)+'<br/>' +
                '<strong>Discount: </strong>'+numberWithCommas(discount)+'<br/>' +
                '<strong>Discount Total Contract Price: </strong>'+numberWithCommas(discounted_tcp)+'<br/>' +
                '<strong>Reservation Fee: </strong>'+numberWithCommas(reservation_fee)+'<br/>' +
                '<strong>Loanable Amount: </strong>'+numberWithCommas(loanable_amount)+'<br/>' +
                '<strong>Net Equity Payment: </strong>'+numberWithCommas(discounted_tcp_less_loanable)+'<br/>' +
                '<strong>Months to pay: </strong>'+months_to_pay+' months<br/>' +
                '<strong>Monthly Amort. NEP: </strong>'+numberWithCommas(man_NEP.toFixed(2))+'<br/>' +
                '</div>';

            return content;
        });
    }
});

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

$(document).on('click','.apec-calculator-reset',function(){
    $('.text-danger').remove();
    $('.calculation-result').html("");
});
