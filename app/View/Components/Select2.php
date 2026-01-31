<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select2 extends Component
{
    public string $name;
    public string $id;
    public string $class;
    public string $parent;
    public string $keyName;
    public string $valueName;
    public array $options;
    public string $selected;
    public string $placeholder;
    public bool $disabled;
    public bool $required;

    public function __construct(
        string $name = '',
        string $id = '',
        string $class = '',
        string $parent = '',
        string $keyName = '',
        string $valueName = '',
        array $options = [],
        string $selected = '',
        string $placeholder = '',
        bool $disabled = false,
        bool $required = false
    ) {
        $this->name = $name;
        $this->id = $id;
        $this->class = $class;
        $this->parent = $parent;
        $this->keyName = $keyName;
        $this->valueName = $valueName;
        $this->options = $options;
        $this->selected = $selected;
        $this->placeholder = $placeholder;
        $this->disabled = $disabled;
        $this->required = $required;
    }

    public function render()
    {
        return view('components.select2');
    }
}
