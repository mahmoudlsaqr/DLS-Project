<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterColumns extends Component
{
    public string $class;
    public int $sub;
    public int $start;
    public bool $filters;
    public bool $search;
    public array $columns;
    public bool $checkbox;

    public function __construct(
        array $columns = [], 
        bool $checkbox = true,
        string $class = '',
        int $sub = 0,
        int $start = 1,
        bool $filters = true,
        bool $search = true
    ) {
        $this->class = $class;
        $this->columns = $columns;
        $this->sub = $sub;
        $this->start = $start;
        $this->filters = $filters;
        $this->checkbox = $checkbox;
        $this->search = $search;
    }

    public function render()
    {
        return view('components.filter-columns');
    }
}
