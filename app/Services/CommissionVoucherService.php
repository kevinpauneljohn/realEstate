<?php

namespace App\Services;

use App\CommissionVoucher;
use App\Deduction;

class CommissionVoucherService
{
    public function with_holding_tax_amount($commission_released, $wht): float|int
    {
        return $commission_released * ($wht / 100);
    }

    public function net_commission_less_wht($gross_commission, $wht): float|int
    {
        $converted_wht = 100 - $wht;
        return $gross_commission * ($converted_wht / 100);
    }

    public function vat_amount($net_commission_less_wht, $vat): float|int
    {
        $vat_formula = (100 + 12) / 100;
        $vat_percent_format = $vat / 100;
        return ( $net_commission_less_wht / $vat_formula ) * $vat_percent_format;
    }

    public function net_commission_less_vat($net_commission_less_wht, $vat): float
    {
        $vat_formula = (100 + 12) / 100;
        $vat_percent_format = $vat / 100;
        return $net_commission_less_wht - (( $net_commission_less_wht / $vat_formula ) * $vat_percent_format);
    }

    public function deduction_formatter($deductions): \Illuminate\Support\Collection
    {
        return collect($deductions)->map(function (int $item, int $key) {
            return number_format($item,2);
        });
    }

    private function total_deductions($deductions)
    {
        return !is_null($deductions) ? collect($deductions)->sum() : 0;
    }

    private function released_gross_commission($gross_commission, $percentage_released): float|int
    {
        return $gross_commission * ($percentage_released / 100);
    }

    private function gross_commission($tcp, $sd_rate): float|int
    {
        return $tcp * ($sd_rate / 100);
    }

    private function net_commission_tax_reference_based($gross_commission_based_on_tcp, $with_holding_tax_amount, $vat_amount)
    {
        return $gross_commission_based_on_tcp - ($with_holding_tax_amount + $vat_amount);
    }

    public function voucherPreview($request): array
    {
        $gross_commission_amount = $this->is_reference_amount_box_checked($request) ?
            $this->released_gross_commission($request->sub_total_reference_amount, $request->requested_rate):
            $request->sub_total;
        $gross_commission_value = $this->is_reference_amount_box_checked($request)? $request->reference_amount : $request->total_contract_price;
        $gross_commission = $this->gross_commission($gross_commission_value, $request->requested_rate);
        $gross_commission_based_on_tcp = $this->gross_commission($request->total_contract_price, $request->requested_rate);
        $net_commission_less_wht = $this->net_commission_less_wht($gross_commission_amount,$request->wht);
        $net_commission_less_vat = $this->net_commission_less_vat($net_commission_less_wht, $request->vat);
        $tax_basis = $this->is_reference_amount_box_checked($request);
        $wht = $request->wht;
        $vat = $request->vat;
        $percentage_released = $this->is_reference_amount_box_checked($request) ? $request->percentage_released_reference_amount :
            $request->percentage_released;
        $released_gross_commission = $this->released_gross_commission($gross_commission, $percentage_released);
        $with_holding_tax_amount = $this->with_holding_tax_amount($released_gross_commission, $wht);
        $vat_amount = $this->vat_amount($net_commission_less_wht, $vat);
        $commission_receivable = !$this->is_reference_amount_box_checked($request) ? $net_commission_less_vat :
            $this->net_commission_tax_reference_based($gross_commission_based_on_tcp, $with_holding_tax_amount, $vat_amount);
        return [
            'tax_basis_reference' => $tax_basis,
            'tax_basis_reference_remarks' => $request->remarks,
            'tax_basis_reference_amount' => '₱ '.number_format($request->reference_amount,2),
            'tcp' => number_format($request->total_contract_price,2),
            'requested_rate' => number_format($request->requested_rate,2).'%',
            'gross_commission' => number_format($request->gross_commission,2),
            'percentage_released' => $this->is_reference_amount_box_checked($request) ?
                number_format($request->percentage_released_reference_amount,2).'%' :
                number_format($request->percentage_released,2).'%',
            'sub_total' => number_format($gross_commission_amount,2),
            'wht_percent' => number_format($request->wht),
            'wht' => number_format($this->with_holding_tax_amount($gross_commission_amount,$request->wht),2),
            'vat_percent' => number_format($request->vat),
            'vat' => number_format($this->vat_amount($net_commission_less_wht, $request->vat),2),
            'deductions' => collect($request->deductions_remarks)->combine($this->deduction_formatter($request->deductions)),
            'net_commission_less_vat' => number_format($net_commission_less_vat,2),
            'total_receivable_without_deduction' => number_format($commission_receivable,2),
            'net_commission_less_deductions' => number_format($commission_receivable - $this->total_deductions($request->deductions),2)
        ];
    }

    public function save($request)
    {
        $gross_commission_amount = $this->is_reference_amount_box_checked($request) ?
            $this->released_gross_commission($request->sub_total_reference_amount, $request->requested_rate):
            $request->sub_total;
        $gross_commission_value = $this->is_reference_amount_box_checked($request)? $request->reference_amount : $request->total_contract_price;
        $gross_commission = $this->gross_commission($gross_commission_value, $request->requested_rate);
        $gross_commission_based_on_tcp = $this->gross_commission($request->total_contract_price, $request->requested_rate);
        $net_commission_less_wht = $this->net_commission_less_wht($gross_commission_amount,$request->wht);
        $net_commission_less_vat = $this->net_commission_less_vat($net_commission_less_wht, $request->vat);
        $wht = $request->wht;
        $vat = $request->vat;
        $percentage_released = $this->is_reference_amount_box_checked($request) ? $request->percentage_released_reference_amount :
            $request->percentage_released;
        $released_gross_commission = $this->released_gross_commission($gross_commission, $percentage_released);
        $with_holding_tax_amount = $this->with_holding_tax_amount($released_gross_commission, $wht);
        $vat_amount = $this->vat_amount($net_commission_less_wht, $vat);
        $commission_receivable = !$this->is_reference_amount_box_checked($request) ? $net_commission_less_vat :
            $this->net_commission_tax_reference_based($gross_commission_based_on_tcp, $with_holding_tax_amount, $vat_amount);
        $commissionVoucher = CommissionVoucher::create([
            'sale_id' => $request->sale_id,
            'commission_request_id' => $request->commission_request_id,

            'tax_basis' => $this->is_reference_amount_box_checked($request),
            'tax_basis_reference_remarks' => $request->remarks,
            'tax_basis_reference_amount' => $request->reference_amount,
            'tcp' => $request->total_contract_price,
            'requested_rate' => $request->requested_rate,

            'gross_commission' => $request->gross_commission,
            'percentage_released' => $this->is_reference_amount_box_checked($request) ?
                $request->percentage_released_reference_amount:
                $request->percentage_released,
            'sub_total' => $gross_commission_amount,
            'wht_percent' => $request->wht,
            'wht_amount' => $this->with_holding_tax_amount($request->sub_total,$request->wht),
            'vat_percent' => $request->vat,
            'vat_amount' => $this->vat_amount($net_commission_less_wht, $request->vat),
            'net_commission_less_vat' => $net_commission_less_vat,

            'total_receivable_without_deduction' => $commission_receivable,

            'net_commission_less_deductions' => $commission_receivable - $this->total_deductions($request->deductions),
            'status' => 'pending'
        ]);
        $this->deductions(collect($request->deductions_remarks)->combine($request->deductions), $commissionVoucher->id);
        return $commissionVoucher;
    }

    private function deductions($deductions, $commission_voucher_id)
    {
        foreach($deductions as $key => $deduction)
        {
            Deduction::create([
                'commission_voucher_id' => $commission_voucher_id,
                'title' => $key,
                'amount' => $deduction
            ]);
        }
    }

    private function is_reference_amount_box_checked($request): bool
    {
        return collect($request->all())->has('reference_amount_checkbox');
    }
}
