<?php 
namespace Congress\Components\Panels;

use PComp\{View, Component};
use Congress\Components\Link;

class FeatureButton extends Component
{
    protected string $url = '';
    protected string $label;
    protected string $iconUrl;

    protected function markup() : Component|array|null
    {
        return View::component(Link::class, 
        class: 'flex flex-col items-center justify-center w-40 h-40 border border-neutral-700 rounded hover:backdrop-brightness-75 mr-2 mb-2', 
        url: $this->url, 
        children: 
        [ 
            View::scTag('img', class: 'block h-20 mb-2', src: $this->iconUrl, alt: $this->label),
            View::tag('div', class: 'text-center', children: [ View::text($this->label)  ]) 
        ]);
    }
}