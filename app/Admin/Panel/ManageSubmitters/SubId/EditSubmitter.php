<?php
namespace Congress\App\Admin\Panel\ManageSubmitters\SubId;

use Congress\Components\Label;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Submitters\Submitter;
use PComp\{View, Component, Context, HeadManager};

class EditSubmitter extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador: Editar autor";

        $conn = Connection::get();

        try
        {
            $subGetter = new Submitter([ 'id' => $this->id ]);
            $this->submitter = $subGetter->getSingle($conn);
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    } 

    protected int $id;
    protected Submitter $submitter;

    protected function markup() : Component|array|null
    {
        return isset($this->submitter) ?
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Editar autor'),

            View::tag('form', method: 'post', action: URLGenerator::generateScriptUrl('admin/man_submitters/edit.php', [ 'id' => $this->submitter->id ]), children:
            [
                View::component(Label::class, label: 'ID', children: [ View::text($this->submitter->id) ]),
                View::tag('fieldset', class: 'fieldset', children: 
                [
                    View::tag('legend', children: [ View::text('Pessoal') ]),
                    View::component(Label::class, label: 'Nome', children: 
                    [ 
                        View::scTag('input', type: 'text', maxlength: 140, size: 40, value: Data::hscq($this->submitter->name), required: 'req', name: 'submitters:txtName')
                    ]),
                    View::component(Label::class, label: 'E-mail', children: 
                    [ 
                        View::scTag('input', type: 'email', maxlength: 140, size: 40, value: Data::hscq($this->submitter->email), required: 'req', name: 'submitters:txtEmail')                
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