<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DropdownUpdateSelector extends Component
{
    public string $class;
    public array $columns;

    public function __construct(array $columns = [], string $class = '')
    {
        $this->class = $class;
        $this->columns = $columns;
    }

    public function render(): View|Closure|string
    {
        return view('components.dropdown-update-selector');
    }
}
