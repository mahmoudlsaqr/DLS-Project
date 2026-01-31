<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToggleSwitchStatus extends Component
{
    public string $name;
    public string $id;
    public string $class;
    public string $value;
    public string $dataId;
    public bool $checked;

    public function __construct(
        string $name = 'status',
        string $id = '',
        string $class = '',
        string $value = '',
        string $dataId = '',
        bool $checked = false,
    ) {
        $this->name = $name;
        $this->id = $id ?: 'toggleSwitch' . uniqid();
        $this->class = $class;
        $this->value = $value;
        $this->dataId = $dataId;
        $this->checked = $checked;
    }

    public function render()
    {
        return view('components.toggle-switch-status');
    }
}
