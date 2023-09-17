<?php
namespace Congress\Components;

use PComp\{View, Component};

class PageMessages extends Component
{
    protected function setUp() {} 

    protected function markup() : Component|null
    {
        return null;
    }

    public function render(): void
    {
        $messages = \PComp\Context::get('page_messages') ?? [];

        if (count($messages) > 0)
            View::render(
            [
                View::tag('div', 
                    class: 'rounded px-4 py-2 w-[400px] my-2 mx-auto bg-green-200 text-center text-xl', 
                    children: array_map(fn($m) => View::tag('p', children: [ View::text($m) ]), $messages )
                )
            ]);
    }
}