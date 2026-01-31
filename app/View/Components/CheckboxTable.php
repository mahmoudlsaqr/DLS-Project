<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CheckboxTable extends Component
{
    public string $name;
    public string $id;
    public string $class;
    public bool $checked;
    public bool $disabled;
    public string $dataId;

    public function __construct(
        string $name = '',
        string $id = '',
        string $class = '',
        bool $checked = false,
        bool $disabled = false,
        string $dataId = ''
    ) {
        $this->name = $name;
        $this->id = $id;
        $this->class = $class;
        $this->checked = $checked;
        $this->disabled = $disabled;
        $this->dataId = $dataId;
    }

    public function render()
    {
        return view('components.checkbox-table');
    }
}
