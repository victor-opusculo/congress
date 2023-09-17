<?php 
namespace Congress\Components;

use PComp\{View, Component};

class Label extends Component
{
    protected bool $lineBreak = false;
    protected string $label;
    protected bool $labelBold = false;
    protected bool $reverse = false;

    protected function markup() : Component|array|null
    {
        return View::tag('label', class: 'flex m-2 ' . ($this->lineBreak ? 'flex-col' : 'flex-row items-center'), children:
            !$this->reverse ? 
            [
                View::tag('span', class: 'shrink mr-2 text-base ' . ($this->labelBold ? 'font-bold' : ''), children: [ View::text($this->label . ': ') ]),
                View::tag('span', class: 'grow text-base flex flex-row flex-wrap', children: $this->children)
            ]
            : 
            [
                View::tag('span', class: 'text-base', children: $this->children),
                View::tag('span', class: 'ml-2 text-base' . ($this->labelBold ? 'font-bold' : ''), children: [ View::text($this->label) ])
            ]
        );
    }
}