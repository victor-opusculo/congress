<?php
namespace Congress\App;

use PComp\{View, Component};

require_once __DIR__ . "/../components/NavBar.php";

class Layout extends Component
{
    protected function setUp()
    {
        \PComp\Context::set('user', 'Victor');
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::tag('div', class: 'min-h-[calc(100vh)]', children:
            [
                View::tag('header', children:
                [
                    View::text('Header')
                ]),
                View::component(\Congress\Components\NavBar::class),
                View::component(\Congress\Components\PageMessages::class),
                View::tag('main', children: $this->children)
            ])
        ];
    }
}