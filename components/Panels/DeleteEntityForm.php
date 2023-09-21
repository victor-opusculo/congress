<?php
namespace Congress\Components\Panels;

use PComp\{Component, View};

class DeleteEntityForm extends Component
{
    public function setUp() { }

    protected string $deleteScriptUrl;

    protected function markup(): Component|array|null
    {
        return View::tag('form', method: 'post', action: $this->deleteScriptUrl, children:
        [
            ...$this->children,
            View::tag('div', class: 'text-center my-4', children:
            [
                View::tag('button', type: 'submit', class: 'btn mr-4', children: [ View::text('Sim, excluir') ]),
                View::tag('button', type: 'button', class: 'btn', onclick: 'history.back();', children: [ View::text('Não excluir') ]),
            ])
        ]);
    }
}