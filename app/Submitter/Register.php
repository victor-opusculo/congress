<?php
namespace Congress\App\Submitter;

use Congress\Components\Label;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component, HeadManager, ScriptManager};

class Register extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Cadastro de Autor";
        ScriptManager::registerScript('submitterRegisterScript', file_get_contents(__DIR__ . '/register.js'));
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::component(PageTitle::class, text: 'Cadastro de autor'),
            View::tag('div', class: 'mx-auto max-w-[600px] min-w-[300px] bg-slate-200 border border-slate-700 text-base p-4 mt-4', children:
            [
                View::tag('form', id: 'frmRegister', method: 'post', action: URLGenerator::generateScriptUrl('submitters/register.php'), children: 
                [ 
                    View::component(Label::class, label: 'E-mail', children: [ View::scTag('input', name: "txtEmail", type: 'email', required: 'req', class: 'w-full') ]),
                    View::component(Label::class, label: 'Nome completo', children: [ View::scTag('input', name: "txtName", type: 'text', required: 'req', class: 'w-full') ]),
                    View::component(Label::class, label: 'Senha', children: [ View::scTag('input', name: 'txtPassword', type: 'password', required: 'req', class: 'w-full', id: 'txtPassword') ]),
                    View::component(Label::class, label: 'Confirme a senha', children: [ View::scTag('input', name: 'txtPassword2', type: 'password', required: 'req', class: 'w-full', id: 'txtPassword2') ]),

                    View::tag('div', class: 'text-center', children: 
                    [
                        View::tag('button', type: 'submit', class: 'btn', children: [ View::text('Cadastrar-se') ])
                    ])
                ]),
            ])
        ];
    }
}