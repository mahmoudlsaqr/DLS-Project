<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public ?string $href;

    public function __construct(?string $href = null)
    {
        $this->href = $href;
    }

    public function render()
    {
        return view('components.button');
    }
}