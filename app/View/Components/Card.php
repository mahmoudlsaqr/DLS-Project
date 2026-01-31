<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public string $title;
    public string $cardLabel;
    public string $cardBody;
    public bool $footer;
    public bool $header;

    public function __construct(string $title = '', string $cardLabel = '', string $cardBody = '', bool $footer = false, bool $header = true)
    {
        $this->title = $title;
        $this->cardLabel = $cardLabel;
        $this->cardBody = $cardBody;
        $this->footer = $footer;
        $this->header = $header;
    }

    public function render()
    {
        return view('components.card');
    }
}
