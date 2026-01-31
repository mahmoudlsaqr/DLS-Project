<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormLabel extends Component
{
    public string $label;
    public string $for;
    public bool $required;
    public string $class;
    public string $description;

    public function __construct(string $label, string $for = '', bool $required = false, string $class = '', string $description = '')
    {
        $this->label = $label;
        $this->for = $for;
        $this->required = $required;
        $this->class = $class;
        $this->description = $description;
    }

    public function render()
    {
        return view('components.form-label');
    }
}
