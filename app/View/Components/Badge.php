<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    public $color;

    public function __construct($color = 'secondary')
    {
        $this->color = $color;
    }

    public function render()
    {
        return view('components.badge');
    }
}
