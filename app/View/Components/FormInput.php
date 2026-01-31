<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    public string $type;
    public string $name;
    public string $id;
    public string $class;
    public string $value;
    public string $placeholder;
    public string $groupText;
    public bool $group;
    public string $oninput;
    public bool $required;
    public $attributes;

    public function __construct(
        string $type = 'text',
        string $name = '',
        string $id = '',
        string $class = '',
        string $value = '',
        string $placeholder = '',
        string $oninput = '',
        bool $required = false,
        bool $group = false,
        string $groupText = '',
        $attributes = []
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->id = $id;
        $this->class = $class;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->oninput = $oninput;
        $this->required = $required;
        $this->group = $group;
        $this->groupText = $groupText;
        $this->attributes = is_array($attributes) ? $attributes : [];
    }

    public function render()
    {
        return view('components.form-input');
    }
}
