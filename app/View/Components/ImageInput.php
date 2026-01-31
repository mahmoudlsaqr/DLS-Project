<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImageInput extends Component
{
    public string $name;
    public string $id;
    public string $class;
    public string $avatar;
    public string $placeholder;

    public function __construct(
        string $name = '',
        string $id = '',
        string $class = '',
        string $avatar = '',
        string $placeholder = ''
    ) {
        $this->name = $name;
        $this->id = $id;
        $this->class = $class;
        $this->avatar = $avatar ?: asset('assets/media/misc/pattern-4.jpg');
        $this->placeholder = $placeholder ?: asset('assets/media/misc/pattern-4.jpg');
    }

    public function render()
    {
        return view('components.image-input');
    }
}
