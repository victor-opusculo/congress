<?php
namespace Congress\App\Admin\Panel\ManageAssessors\AssId;

use Congress\Components\Label;
use Congress\Components\Panels\DeleteEntityForm;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;
use PComp\{View, Component, Context, HeadManager};

class DeleteAssessor extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador: Excluir avaliador";

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
            View::component(PageTitle::class, tag: 'h2', text: 'Excluir avaliador'),

            View::component(DeleteEntityForm::class, 
                            deleteScriptUrl: URLGenerator::generateScriptUrl('/admin/man_assessors/delete.php', [ 'id' => $this->assessor->id ]),
                            children:
                            [
                                View::tag('div', class: 'text-center font-bold mb-2', children: [ View::text('Você realmente deseja excluir este avaliador? Esta ação é irreversível!') ]),
                                View::component(Label::class, label: 'ID', labelBold: true, children: [ View::text($this->assessor->id) ] ),
                                View::component(Label::class, label: 'Nome', labelBold: true, children: [ View::text($this->assessor->name) ] ),
                                View::component(Label::class, label: 'E-mail', labelBold: true, children: [ View::text($this->assessor->email) ] )
                            ])
        ]: 
        null;
    }
}