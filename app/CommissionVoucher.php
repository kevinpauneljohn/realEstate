<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id','commission_request_id','gross_commission','percentage_released','sub_total',
        'wht_percent','wht_amount','vat_percent','vat_amount','deductions',
        'net_commission_less_vat','net_commission_less_deductions'
    ];

    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }
}
