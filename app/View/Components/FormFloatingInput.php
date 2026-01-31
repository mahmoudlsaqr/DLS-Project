<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormFloatingInput extends Component
{
    public string $type;
    public string $name;
    public string $id;
    public string $placeholder;
    public string $label;
    public string $class;

    public function __construct(
        string $type = 'text',
        string $name = '',
        string $id = '',
        string $placeholder = '',
        string $label = '',
        string $class = ''
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->id = $id;
        $this->placeholder = $placeholder;
        $this->label = $label;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.form-floating-input');
    }
}
