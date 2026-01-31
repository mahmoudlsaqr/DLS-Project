<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DropdownMenu extends Component
{
    public array $buttons;
    public ?string $id;
    public ?string $deleteName;

    public function __construct(array $buttons = [], ?string $id = null, ?string $deleteName = null)
    {
        $this->buttons = $buttons;
        $this->id = $id;
        $this->deleteName = $deleteName;
    }

    public function render()
    {
        return view('components.dropdown-menu');
    }
}
