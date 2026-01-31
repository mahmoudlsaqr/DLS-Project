<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalCreateUpdate extends Component
{
    public string $id;
    public string $title;
    public bool $centered;
    public bool $reset;
    public string $size;

    public function __construct(string $id, string $title = '', bool $centered = true, bool $reset = false, string $size = 'lg')
    {
        $this->id = $id;
        $this->title = $title;
        $this->centered = $centered;
        $this->size = $size;
        $this->reset = $reset;
    }

    public function render()
    {
        return view('components.modal-create-update');
    }
}

