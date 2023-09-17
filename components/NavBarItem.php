<?php
namespace Congress\Components;

use PComp\{View, StyleManager, Component};

require_once "Link.php";

class NavBarItem extends Component
{
    protected string $url = "#";
    protected string $label;

    protected function setUp()
    {
       
    } 

    protected function markup() : Component
    {
        return View::tag('span',
        class: '',
        children:
        [
            View::component(Link::class, class: 'hover:bg-red-500 cursor-pointer inline-block px-4 py-4' , url: $this->url, children: [ View::text($this->label) ])
        ]);
    }
}