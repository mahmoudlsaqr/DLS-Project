<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Checkbox extends Component
{
    public string $name;
    public string $id;
    public string $value;
    public ?string $valueHidden;
    public string $label;
    public bool $checked;
    public string $class;
    public string $type;

    public function __construct(
        string $name,
        string $id = null,
        string $value = null,
        string $valueHidden = null,
        string $label = null,
        bool $checked = false,
        string $type = '',
        string $class = 'form-check-input'
    ) {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->valueHidden = $valueHidden;
        $this->label = $label;
        $this->checked = $checked;
        $this->class = $class;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.checkbox');
    }
}
