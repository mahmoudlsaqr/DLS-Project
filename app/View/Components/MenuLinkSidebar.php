<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class MenuLinkSidebar extends Component
{
    public ?string $route;
    public string $text;
    public ?string $icon;
    public mixed $match;

    public function __construct($route = null, $text, $icon = null, $match = null)
    {
        $this->route = $route;
        $this->text = $text;
        $this->icon = $icon;
        $this->match = $match;
    }

    public function render()
    {
        return view('components.menu-link-sidebar');
    }
}
