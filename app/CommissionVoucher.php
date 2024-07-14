<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id','commission_request_id',
        'tax_basis','tax_basis_reference_remarks','tax_basis_reference_amount',
        'tcp','requested_rate',
        'gross_commission','percentage_released','sub_total',
        'wht_percent','wht_amount','vat_percent','vat_amount',
        'net_commission_less_vat',
        'total_receivable_without_deduction',
        'net_commission_less_deductions','status'
    ];

    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }
}
