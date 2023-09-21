<?php
namespace Congress\App\Admin\Panel;

use Congress\Components\Label;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component, HeadManager, ScriptManager};

class Settings extends Component
{

    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador: Configurações";
        ScriptManager::registerScript('adminSettingsScript', file_get_contents(__DIR__ . '/settings.js'));
    } 

    protected function markup() : Component|array
    {
        return  
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Configurações de Conta'),
            View::tag('form', id: 'frmSettings', method: 'post', action: URLGenerator::generateScriptUrl('admin/settings.php'), children:
            [
                View::tag('fieldset', class: 'fieldset', children: 
                [
                    View::tag('legend', children: [ View::text('Alterar senha') ]),
                    View::component(Label::class, label: 'Senha atual', children:
                    [
                        View::scTag('input', class: 'w-52', type: 'password', maxlength: 140, name: 'txtOldPassword', id: 'txtOldPassword')
                    ]),
                    View::component(Label::class, label: 'Senha nova', children:
                    [
                        View::scTag('input', class: 'w-52', type: 'password', maxlength: 140, name: 'txtNewPassword', id: 'txtNewPassword')
                    ]),
                    View::component(Label::class, label: 'Confirme a senha nova', children:
                    [
                        View::scTag('input', class: 'w-52', type: 'password', maxlength: 140, name: 'txtNewPassword2', id: 'txtNewPassword2')
                    ]),
                ]),

                View::tag('div', class: 'text-center mt-2', children: [ View::tag('button', class:'btn', children: [ View::text('Salvar') ] ) ] )
            ])
        ];
    }
}