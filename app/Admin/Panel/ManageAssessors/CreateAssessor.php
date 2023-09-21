<?php
namespace Congress\App\Admin\Panel\ManageAssessors;

use Congress\Components\{Label, Site\PageTitle};
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Assessors\Assessor;
use PComp\{View, Component, Context, HeadManager};

class CreateAssessor extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador: Criar avaliador";

        try
        {
            $this->assessor = new Assessor();
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    } 

    protected Assessor $assessor;

    protected function markup() : Component|array|null
    {
        return isset($this->assessor) ?
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Criar avaliador'),
            View::tag('form', method: 'post', action: URLGenerator::generateScriptUrl('admin/man_assessors/create.php'), children:
            [
                View::tag('fieldset', class: 'fieldset', children: 
                [
                    View::tag('legend', children: [ View::text('Pessoal') ]),
                    View::component(Label::class, label: 'Nome', children: 
                    [ 
                        View::scTag('input', type: 'text', maxlength: 140, size: 40, value: Data::hscq($this->assessor->name), required: 'req', name: 'assessors:txtName')
                    ]),
                    View::component(Label::class, label: 'E-mail', children: 
                    [ 
                        View::scTag('input', type: 'email', maxlength: 140, size: 40, value: Data::hscq($this->assessor->email), required: 'req', name: 'assessors:txtEmail')                
                    ])
                ]),

                View::tag('fieldset', class: 'fieldset', children: 
                [
                    View::tag('legend', children: [ View::text('Senha') ]),
                    View::component(Label::class, label: 'Senha de acesso', children:
                    [
                        View::scTag('input', type: 'text', maxlength: 140, size: 30, name: 'txtPassword')
                    ])
                ]),

                View::tag('div', class: 'text-center mt-2', children:
                [
                    View::tag('button', type: 'submit', class: 'btn', children: [ View::text('Salvar') ])
                ])
                
            ]) 
        ]: 
        null;
    }
}