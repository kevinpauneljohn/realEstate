<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UnitsReservedTable extends Component
{
    public $lead;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.units-reserved-table');
    }
}
