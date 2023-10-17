<?php
namespace Congress\App;

use Congress\Lib\Helpers\System;
use Congress\Lib\Helpers\URLGenerator;
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
            View::tag('div', class: 'min-h-[calc(100vh-150px)]', children:
            [
                View::tag('header', class: 'block text-center', children:
                [
                    View::scTag('img', class: 'inline', src: URLGenerator::generateFileUrl('assets/pics/logo.png'), alt: System::eventName())
                ]),
                View::component(\Congress\Components\NavBar::class),
                View::component(\Congress\Components\PageMessages::class),
                View::tag('main', children: $this->children)
            ]),
            View::tag('footer', class: 'bg-amber-300 p-4 h-[150px]', children:
            [
                View::tag('p', class: 'text-lg text-center font-bold', children: [ View::text(System::eventName()) ]),
                View::tag('p', class: 'text-base text-center', children: [ View::text("Organização: ***") ]),
                View::tag('p', class: 'text-base text-center', children: [ View::text("Contato: ***") ])
            ])
        ];
    }
}