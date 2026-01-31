<?php

namespace App\View\Components;

use Illuminate\View\Component;

class updateButton extends Component
{
    public int $dataId;
    public string $class;
    public function __construct(int $dataId, string $class = '')
    {
        $this->dataId = $dataId;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.button-update');
    }
}
