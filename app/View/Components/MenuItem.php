<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuItem extends Component
{
    public ?string $route;
    public ?string $class;
    public ?string $dataId;

    public function __construct(?string $route = null, ?string $class = null, ?string $dataId = null)
    {
        $this->route = $route;
        $this->class = $class;
        $this->dataId = $dataId;
    }

    public function render()
    {
        return view('components.menu-item');
    }
}
