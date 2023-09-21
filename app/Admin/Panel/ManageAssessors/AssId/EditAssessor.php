<?php
namespace Congress\App\Admin\Panel\ManageAssessors\AssId;

use Congress\Components\Label;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;
use PComp\{View, Component, Context, HeadManager};

class EditAssessor extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador: Editar avaliador";

        $conn = Connection::get();

        try
        {
            $assessorsGetter = new Assessor([ 'id' => $this->id ]);
            $this->assessor = $assessorsGetter->getSingle($conn);
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    } 

    protected int $id;
    protected Assessor $assessor;

    protected function markup() : Component|array|null
    {
        return isset($this->assessor) ?
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Editar avaliador'),

            View::tag('form', method: 'post', action: URLGenerator::generateScriptUrl('admin/man_assessors/edit.php', [ 'id' => $this->assessor->id ]), children:
            [
                View::component(Label::class, label: 'ID', children: [ View::text($this->assessor->id) ]),
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
                    View::tag('legend', children: [ View::text('Alterar senha') ]),
                    View::component(Label::class, label: 'Nova senha', children:
                    [
                        View::scTag('input', type: 'text', maxlength: 140, size: 30, name: 'txtNewPassword')
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