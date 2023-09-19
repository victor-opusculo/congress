<?php 
namespace Congress\Components\Site;

use PComp\{View, Component};

class PageTitle extends Component
{
    protected string $tag = 'h1';
    protected string $text;

    protected function markup() : Component|array|null
    {
        return View::tag($this->tag, children: [ View::text($this->text) ] );
    }
}