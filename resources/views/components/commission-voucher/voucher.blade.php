<table class="table table-bordered table-hover">
    <tbody><tr>
        <th class="text-center" colspan="4"><img class="rounded img-thumbnail img-fluid img-size-64" src="{{asset('vendor/adminlte/dist/img/dhg_logo_mini.png')}}" alt="dhg logo"> <h4>Dream Home Guide Realty Comm Voucher</h4></th>
    </tr>
    <tr>
        <th id="project-name" colspan="4" class="text-center"></th>
    </tr>
    <tr>
        <td>Payee</td>
        <td id="payee" class="text-bold">{{$commissionRequest->sales->user->fullname}}</td>
        <td>Amount:</td>
        <td id="amount" class="text-bold">@if($commissionVoucher->count() > 0) {{number_format($commissionVoucher->first()->net_commission_less_deductions,2)}} @endif</td>
    </tr>
    <tr>
        <td class="w-25">Client</td>
        <td id="client" class="text-bold w-25">{{$commissionRequest->sales->lead->fullname}}</td>
        <td>In Words:</td>
        <td id="amount-in-words" class="text-bold w-50">@if($commissionVoucher->count() > 0) {{ucwords($netCommissionWords)}} @endif</td>
    </tr>
    <tr>
        <td colspan="4" class="table-active"></td>
    </tr>
    <tr>
        <td colspan="3">TCP</td>
        <td colspan="1" id="tcp">@if($commissionVoucher->count() > 0)&#8369; {{number_format($commissionRequest->sales->total_contract_price,2)}} @endif</td>
    </tr>
    <tr>
        <td colspan="3">Requested %</td>
        <td colspan="1"><span id="requested-rate" class="dhg-hidden">{{number_format($commissionRequest->commission,2)}}%</span></td>
    </tr>
    <tr>
        <td colspan="3">Gross Commission</td>
        <td colspan="1" id="gross-commission">@if($commissionVoucher->count() > 0)&#8369;{{number_format($commissionVoucher->first()->gross_commission,2)}}@endif</td>
    </tr>
    <tr id="tax-basis-row" @if($commissionVoucher->count() < 1 || $commissionVoucher->first()->tax_basis == false) style="display: none;" @endif>
        <td colspan="3" id="tax_basis_reference_remarks">@if($commissionVoucher->count() > 0 && $commissionVoucher->first()->tax_basis == true) {{ucwords(strtolower($commissionVoucher->first()->tax_basis_reference_remarks))}} @endif</td>
        <td colspan="1" id="tax-basis">@if($commissionVoucher->count() > 0 && $commissionVoucher->first()->tax_basis == true){{number_format($commissionVoucher->first()->tax_basis_reference_amount,2)}}@endif</td>
    </tr>
    <tr>
        <td colspan="3"><span id="percent-released">@if($commissionVoucher->count() === 0) 0 @else {{$commissionVoucher->first()->percentage_released}} @endif%</span> Released</td>
        <td colspan="1" id="released-gross-commission">@if($commissionVoucher->count() > 0)&#8369; {{number_format($commissionVoucher->first()->sub_total,2)}} @endif</td>
    </tr>
    <tr>
        <td colspan="3">Withholding Tax <span id="wht-percent">@if($commissionVoucher->count() === 0) 0 @else {{$commissionVoucher->first()->wht_percent}} @endif%</span></td>
        <td colspan="1" id="wht">@if($commissionVoucher->count() > 0)&#8369; {{number_format($commissionVoucher->first()->wht_amount,2)}} @endif</td>
    </tr>
    <tr>
        <td colspan="3">VAT <span id="vat-percent">@if($commissionVoucher->count() === 0) 0 @else {{$commissionVoucher->first()->vat_percent}} @endif%</span></td>
        <td colspan="1" id="vat-amount">@if($commissionVoucher->count() > 0)&#8369; {{number_format($commissionVoucher->first()->vat_amount,2)}} @endif</td>
    </tr>
    <tr class="net-commission">
        <td colspan="3">Net Commission</td>
        <td colspan="1" id="net-commission">@if($commissionVoucher->count() > 0)&#8369;
            @if($commissionVoucher->first()->tax_basis == true) {{number_format($commissionVoucher->first()->total_receivable_without_deduction,2)}}@else
                {{number_format($commissionVoucher->first()->net_commission_less_vat,2)}} @endif
            @endif
        </td>
    </tr>

    @if($commissionVoucher->count() > 0)
        @foreach($commissionVoucher->first()->deductions as $deduction)
            <tr>
                <td colspan="3">{{$deduction->title}}</td>
                <td class="text-danger">- &#8369; {{number_format($deduction->amount,2)}}</td>
            </tr>
        @endforeach
    @endif
    @if($commissionVoucher->count() > 0)
        @if($commissionVoucher->first()->deductions->count() > 0)
            <tr>
                <td colspan="3">Total Commission Balance</td>
                <td class="text-success"> &#8369; {{number_format($commissionVoucher->first()->net_commission_less_deductions,2)}}</td>
            </tr>
        @endif
    @endif
    <tr id="row-separator">
        <td colspan="4" class="table-active"></td>
    </tr>
    </tbody></table>
