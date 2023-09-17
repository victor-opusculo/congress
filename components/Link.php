<?php 
namespace Congress\Components;

use PComp\{View, Component};

class Link extends Component
{
    protected string $url;
    protected string $class = '';

    protected function markup() : Component|array|null
    {
        return View::tag('a', class: $this->class, href: $this->url, children: $this->children );
    }
}