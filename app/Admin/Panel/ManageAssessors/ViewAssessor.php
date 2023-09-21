<?php
namespace Congress\App\Admin\Panel\ManageAssessors;

use Congress\Components\Label;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;
use PComp\{View, Component, Context, HeadManager};

class ViewAssessor extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Painel do Avaliador: Ver avaliador";

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
            View::component(Label::class, label: 'ID', labelBold: true, children: [ View::text($this->assessor->id) ]),
            View::component(Label::class, label: 'Nome', labelBold: true, children: [ View::text($this->assessor->name) ]),
            View::component(Label::class, label: 'E-mail', labelBold: true, children: [ View::text($this->assessor->email) ])
        ] : 
        null;
    }
}