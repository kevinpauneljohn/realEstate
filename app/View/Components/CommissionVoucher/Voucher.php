<?php

namespace App\View\Components\CommissionVoucher;

use Illuminate\View\Component;

class Voucher extends Component
{
    public $commissionRequest;
    public $commissionVoucher;
    public string $netCommissionWords;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($commissionRequest, $commissionVoucher, $netCommissionWords)
    {
        $this->commissionRequest = $commissionRequest;
        $this->commissionVoucher = $commissionVoucher;
        $this->netCommissionWords = $netCommissionWords;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.commission-voucher.voucher');
    }
}
