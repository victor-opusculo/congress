<?php
namespace Congress\App\Spectator;

use PComp\{Component, View};
use Congress\Lib\Helpers\URLGenerator;
use Congress\Components\{Label, Site\PageTitle};
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Settings\SpectatorSubscriptionsClosureDate;
use DateTime;

require_once __DIR__ . '/../../vendor/autoload.php';

class Register extends Component
{
    public function setUp()
    {
        $conn = Connection::get();
        $closure = date_create((new SpectatorSubscriptionsClosureDate)->getSingle($conn)->value ?? 'now');
        $today = date_create('now');

        $this->isClosed = $today >= $closure;
    }

    private bool $isClosed;

    protected function markup(): Component|array|null
    {
        return
        [
            View::component(PageTitle::class, text: 'Inscrição de Espectador'),

            !$this->isClosed ?
            View::tag('div', class: 'mx-auto max-w-[600px] min-w-[300px] bg-slate-200 border border-slate-700 text-base p-4 my-4', children:
            [
                View::tag('form', id: 'frmRegister', method: 'post', action: URLGenerator::generateScriptUrl('spectators/register.php'), children: 
                [ 
                    View::component(Label::class, label: 'E-mail', children: [ View::scTag('input', name: "specSubscriptions:txtEmail", type: 'email', required: 'req', class: 'w-full', maxlength: 200) ]),
                    View::component(Label::class, label: 'Nome completo', children: [ View::scTag('input', name: "specSubscriptions:txtName", type: 'text', required: 'req', class: 'w-full', maxlength: 200) ]),
                    
                    View::component(Label::class, label: 'Número de telefone (com DDD)', children: [ View::scTag('input', name: 'specSubscriptions:txtTelephone', type: 'text', required: 'req', class: 'w-full', maxlength: 50) ]),
                    View::component(Label::class, label: 'Cidade de origem', children: [ View::scTag('input', name: 'specSubscriptions:txtCity', type: 'text', required: 'req', class: 'w-full', maxlength: 200) ]),
                    View::component(Label::class, label: 'Estado (UF) de origem', children: [ View::scTag('input', name: 'specSubscriptions:txtStateUf', type: 'text', required: 'req', size: 5, maxlength: 2 ) ]),
                    View::component(Label::class, label: 'Área de interesse', children: [ View::scTag('input', name: 'specSubscriptions:txtAreaOfInterest', type: 'text', class: 'w-full', maxlength: 280) ]),
                    View::component(Label::class, label: 'Tem alguma deficiência? Se sim, especifique', lineBreak: true, children: [ View::scTag('input', name: 'specSubscriptions:txtDisabilityInfo', type: 'text', class: 'w-full', maxlength: 280) ]),

                    View::tag('div', class: 'text-center', children: 
                    [
                        View::tag('button', type: 'submit', class: 'btn', children: [ View::text('Inscrever-se') ])
                    ])
                ]),
            ])
            :
            View::tag('div', class: 'mx-auto max-w-[600px] min-w-[300px] bg-slate-200 border border-slate-700 text-base p-4 my-4', children:
            [
                View::tag('p', children: [ View::text('Inscrições encerradas!') ])
            ])
        ];
    }
}