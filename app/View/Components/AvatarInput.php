<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AvatarInput extends Component
{
    public string $name;
    public string $id;
    public string $class;
    public string $avatar;
    public string $placeholder;

    public function __construct(
        string $name,
        string $id = '',
        string $class = '',
        string $avatar = '',
        string $placeholder = ''
    ) {
        $this->name = $name;
        $this->id = $id;
        $this->class = $class;
        $this->avatar = $avatar ?: asset('assets/media/svg/avatars/blank.svg');
        $this->placeholder = $placeholder ?: asset('assets/media/svg/avatars/blank.svg');
    }

    public function render()
    {
        return view('components.avatar-input');
    }
}
