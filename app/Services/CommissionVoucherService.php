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

    public function voucherPreview($request): array
    {
        $net_commission_less_wht = $this->net_commission_less_wht($request->sub_total,$request->wht);
        $net_commission_less_vat = $this->net_commission_less_vat($net_commission_less_wht, $request->vat);
        return [
            'tcp' => number_format($request->total_contract_price,2),
            'requested_rate' => number_format($request->requested_rate,2).'%',
            'gross_commission' => number_format($request->gross_commission,2),
            'percentage_released' => number_format($request->percentage_released,2).'%',
            'sub_total' => number_format($request->sub_total,2),
            'wht_percent' => number_format($request->wht),
            'wht' => number_format($this->with_holding_tax_amount($request->sub_total,$request->wht),2),
            'vat_percent' => number_format($request->vat),
            'vat' => number_format($this->vat_amount($net_commission_less_wht, $request->vat),2),
            'deductions' => collect($request->deductions_remarks)->combine($this->deduction_formatter($request->deductions)),
            'net_commission_less_vat' => number_format($net_commission_less_vat,2),
            'net_commission_less_deductions' => number_format($net_commission_less_vat - $this->total_deductions($request->deductions),2)
        ];
    }

    public function save($request)
    {
        $net_commission_less_wht = $this->net_commission_less_wht($request->sub_total,$request->wht);
        $net_commission_less_vat = $this->net_commission_less_vat($net_commission_less_wht, $request->vat);
        $commissionVoucher = CommissionVoucher::create([
            'sale_id' => $request->sale_id,
            'commission_request_id' => $request->commission_request_id,
            'gross_commission' => $request->gross_commission,
            'percentage_released' => $request->percentage_released,
            'sub_total' => $request->sub_total,
            'wht_percent' => $request->wht,
            'wht_amount' => $this->with_holding_tax_amount($request->sub_total,$request->wht),
            'vat_percent' => $request->vat,
            'vat_amount' => $this->vat_amount($net_commission_less_wht, $request->vat),
            'net_commission_less_vat' => $net_commission_less_vat,
            'net_commission_less_deductions' => $net_commission_less_vat - $this->total_deductions($request->deductions),
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
}
