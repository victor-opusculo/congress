<?php 
namespace Congress\Components\Panels;

use PComp\{View, Component};

class ButtonsContainer extends Component
{
    protected function markup() : Component|array|null
    {
        return View::tag('div', class: 'flex lg:flex-row flex-col items-center justify-center', children: $this->children );
    }
}