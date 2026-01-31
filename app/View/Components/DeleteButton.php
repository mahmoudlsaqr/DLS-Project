<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteButton extends Component
{
    public string $id;
    public string $deleteName;

    public function __construct(string $id, ?string $deleteName = null)
    {
        $this->id = $id;
        $this->deleteName = $deleteName;
    }

    public function render()
    {
        return view('components.delete-button');
    }
}
