<?php
namespace Congress\App\Admin;

use Congress\Components\Label;
use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component, HeadManager};

class Login extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Log-in de Administrador";
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::tag('div', class: 'mx-auto max-w-[600px] min-w-[300px] bg-slate-200 border border-slate-700 text-base p-4 mt-4', children:
            [
                View::tag('form', method: 'post', action: URLGenerator::generateScriptUrl('admin/login.php'), children: 
                [ 
                    View::component(Label::class, label: 'Senha', children: [ View::scTag('input', name: 'txtPassword', type: 'password', required: 'req', class: 'w-full') ]),

                    View::tag('div', class: 'text-center', children: 
                    [
                        View::tag('button', type: 'submit', class: 'btn', children: [ View::text('Entrar') ])
                    ])
                ]),
            ])
        ];
    }
}