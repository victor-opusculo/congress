<?php
namespace Congress\App\Admin\Panel\ManageSubmitters\SubId;

use Congress\Components\Label;
use Congress\Components\Panels\ConvenienceLinks;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Submitters\Submitter;
use PComp\{View, Component, Context, HeadManager};

class ViewSubmitter extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador: Ver autor";

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
            View::component(PageTitle::class, tag: 'h2', text: 'Ver autor'),
            View::component(Label::class, label: 'ID', labelBold: true, children: [ View::text($this->submitter->id) ]),
            View::component(Label::class, label: 'Nome', labelBold: true, children: [ View::text($this->submitter->name) ]),
            View::component(Label::class, label: 'E-mail', labelBold: true, children: [ View::text($this->submitter->email) ]),

            View::component(ConvenienceLinks::class, 
                            editUrl: URLGenerator::generatePageUrl("/admin/panel/man_submitters/{$this->submitter->id}/edit"),
                            deleteUrl: URLGenerator::generatePageUrl("/admin/panel/man_submitters/{$this->submitter->id}/delete"))
        ] : 
        null;
    }
}