<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class indicator extends Component
{
    public bool $fresh;
    public function __construct(bool $fresh = false)
    {
        $this->fresh = $fresh;
    }

    public function render()
    {
        return view('components.indicator');
    }
}
