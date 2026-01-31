<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class MenuDropdownSidebar extends Component
{
    public ?string $title;
    public ?string $icon;
    public ?string $match;

    public function __construct($title, $icon, $match)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->match = $match;
    }

    public function isActive(): bool
    {
        $route = request()->route()->getName();
        return str_starts_with($route, $this->match);
    }

    public function render()
    {
        return view('components.menu-dropdown-sidebar');
    }
}
