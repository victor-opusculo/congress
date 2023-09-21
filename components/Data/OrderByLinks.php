<?php
namespace Congress\Components\Data;

use Congress\Lib\Helpers\QueryString;
use PComp\Component;
use PComp\View;

class OrderByLinks extends Component
{
    public function setUp()
    {
    }

    protected array $linksDefinitions;

	protected function markup(): Component|array|null
    {
        return View::tag('div', class: 'text-right my-2', children:
        [
            View::tag('span', children: [ View::text('Ordem de Exibição') ]),
            ...array_map(fn($label, $value) => 
                View::tag('a', class: 'link text-lg', href: '?' . QueryString::getQueryStringForHtmlExcept('order_by') . QueryString::formatNew('order_by', $value), children: [ View::text($label) ]),
                array_keys($this->linksDefinitions),
                array_values($this->linksDefinitions)
            )
        ]);
    }
}