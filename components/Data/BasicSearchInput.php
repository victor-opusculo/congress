<?php
namespace Congress\Components\Data;

use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use PComp\Component;
use PComp\View;

class BasicSearchInput extends Component
{
    public function setUp()
    {
    }

	protected function markup(): Component|array|null
    {
        return View::tag('form', class: 'my-2', method: 'get', children:
        [
            View::tag('span', class: 'flex flex-row items-center', children:
            [
                !URLGenerator::$useFriendlyUrls ? View::scTag('input', type: 'hidden', name: 'page', value: $_GET['page'] ?? '/') : null,
                View::tag('label', children:
                [
                    View::text('Pesquisar: '),
                    View::scTag('input', type: 'search', size: 40, name: 'q', maxlength: 280, value: Data::hscq($_GET['q'] ?? '')),
                ]),
                View::tag('button', type: 'submit', class: 'btn min-w-0 ml-2 py-2', children: 
                [
                    View::scTag('img', alt: 'Pesquisar', src: URLGenerator::generateFileUrl('assets/pics/search.png'))
                ])
            ])
        ]);
    }
}