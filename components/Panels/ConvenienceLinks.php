<?php
namespace Congress\Components\Panels;

use Congress\Components\Link;
use PComp\Component;
use PComp\View;

class ConvenienceLinks extends Component
{
    public function setUp() { }

    protected ?string $editUrl = null;
    protected ?string $deleteUrl = null;

    protected function markup(): Component|array|null
    {
        return View::tag('div', class: 'w-full text-left p-2', children:
        [
            $this->editUrl ? View::component(Link::class, class: 'link text-lg mr-2', url: $this->editUrl, children: [ View::text('Editar') ]) : null,
            $this->deleteUrl ? View::component(Link::class, class: 'link text-lg', url: $this->deleteUrl, children: [ View::text('Excluir') ]) : null
        ]);
    }
}