<?php
namespace Congress\App\Assessor\Panel;

use Congress\Components\Label;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;
use PComp\{View, Component, HeadManager, ScriptManager};

class Settings extends Component
{

    protected function setUp()
    {
		HeadManager::$title = "Painel do Avaliador: Configurações";
        ScriptManager::registerScript('assessorSettingsScript', file_get_contents(__DIR__ . '/settings.js'));
        $this->assessor = (new Assessor([ 'id' => $_SESSION['assessor_id'] ]))->getSingle(Connection::get());
    } 

    protected Assessor $assessor;

    protected function markup() : Component|array
    {
        return  
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Configurações de Conta'),
            View::tag('form', id: 'frmSettings', method: 'post', action: URLGenerator::generateScriptUrl('assessors/settings.php'), children:
            [
                View::tag('fieldset', class: 'fieldset', children: 
                [
                    View::tag('legend', children: [ View::text('Pessoal') ]),
                    View::component(Label::class, label: 'E-mail', children:
                    [
                        View::scTag('input', required: 'req', class: 'w-full', type: 'email', maxlength: 140, name: 'assessors:txtEmail', value: Data::hscq($this->assessor->email))
                    ]),
                    View::component(Label::class, label: 'Nome completo', children:
                    [
                        View::scTag('input', required: 'req', class: 'w-full', type: 'text', maxlength: 140, name: 'assessors:txtName', value: Data::hscq($this->assessor->name))
                    ])
                ]),

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