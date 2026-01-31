<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTable extends Component
{
    public array $columns;
    public string $id;
    public string $class;
    public string $classHeader;
    public bool $checkbox;
    public bool $footer;

    public function __construct(
        array $columns,
        bool $checkbox = true,
        string $id = 'data-table',
        string $class = '',
        bool $footer = false,
        string $classHeader = ''
    ) {
        $this->columns = $columns;
        $this->id = $id;
        $this->class = $class;
        $this->classHeader = $classHeader;
        $this->checkbox = $checkbox;
        $this->footer = $footer;
    }

    public function render()
    {
        return view('components.data-table');
    }
}
