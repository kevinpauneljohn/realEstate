<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = ['commission_voucher_id','title','amount'];

    public function commissionVoucher()
    {
        return $this->belongsTo(CommissionVoucher::class);
    }
}
