<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormTextarea extends Component
{
    public string $name;
    public string $id;
    public string $class;
    public string $placeholder;
    public string $value;
    public bool $required;
    public int $rows;

    public function __construct(
        string $name,
        string $id = '',
        string $class = '',
        string $placeholder = '',
        string $value = '',
        bool $required = false,
        int $rows = 3
    ) {
        $this->name = $name;
        $this->id = $id ?: $name;
        $this->class = $class;
        $this->placeholder = empty($placeholder)? __("Write something") : $placeholder;
        $this->value = $value;
        $this->required = $required;
        $this->rows = $rows;
    }

    public function render()
    {
        return view('components.form-textarea');
    }
}
