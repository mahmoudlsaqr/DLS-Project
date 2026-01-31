<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchInput extends Component
{
    public ?string $name;
    public ?string $placeholder;
    public ?string $class;

    public function __construct(?string $name = 'table_search', ?string $placeholder = null, ?string $class = null)
    {
        $this->name = $name;
        $this->placeholder = $placeholder ?? __('Search');
        $this->class = $class;
    }

    public function render()
    {
        return view('components.search-input');
    }
}
