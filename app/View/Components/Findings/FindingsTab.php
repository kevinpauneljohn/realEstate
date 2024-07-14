<?php

namespace App\View\Components\Findings;

use App\Finding;
use Illuminate\View\Component;

class FindingsTab extends Component
{
    public $commissionRequest;
    public $findings;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($commissionRequest)
    {
        $this->commissionRequest = $commissionRequest;
        $this->findings = Finding::where('commission_request_id',$commissionRequest->id)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.findings.findings-tab');
    }
}
