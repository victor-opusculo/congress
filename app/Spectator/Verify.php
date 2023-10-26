<?php
namespace Congress\App\Spectator;

use PComp\{Component, View};
use Congress\Lib\Helpers\URLGenerator;
use Congress\Components\{Label, Site\PageTitle};

require_once __DIR__ . '/../../vendor/autoload.php';

class Verify extends Component
{
    protected function markup(): Component|array|null
    {
        return
        [
            View::component(PageTitle::class, text: 'Verificar Inscrição de Espectador'),
            View::tag('div', class: 'mx-auto max-w-[600px] min-w-[300px] bg-slate-200 border border-slate-700 text-base p-4 my-4', children:
            [
                View::tag('form', method: 'post', action: URLGenerator::generateScriptUrl('spectators/verify.php'), children: 
                [ 
                    View::component(Label::class, label: 'E-mail', children: [ View::scTag('input', name: "specSubscriptions:txtEmail", type: 'email', required: 'req', class: 'w-full', maxlength: 200) ]),
    
                    View::tag('div', class: 'text-center', children: 
                    [
                        View::tag('button', type: 'submit', class: 'btn', children: [ View::text('Verificar') ])
                    ])
                ]),
            ])
        ];
    }
}