<?php
namespace Congress\App\Admin\Panel\ManageSpectators\SpecId;

use Congress\Components\Label;
use Congress\Components\Panels\DeleteEntityForm;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Spectators\SpectatorSubscription;
use PComp\{View, Component, Context, HeadManager};

class DeleteSpectator extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador: Excluir espectador";

        $conn = Connection::get();

        try
        {
            $specGetter = new SpectatorSubscription([ 'id' => $this->id ]);
            $specGetter->setCryptKey(Connection::getCryptoKey());
            $this->spec = $specGetter->getSingle($conn);
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    } 

    protected int $id;
    protected SpectatorSubscription $spec;

    protected function markup() : Component|array|null
    {
        return isset($this->spec) ?
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Excluir espectador'),

            View::component(DeleteEntityForm::class, 
                            deleteScriptUrl: URLGenerator::generateScriptUrl('/admin/man_spectators/delete.php', [ 'id' => $this->spec->id ]),
                            children:
                            [
                                View::tag('div', class: 'text-center font-bold mb-2', children: [ View::text('Você realmente deseja excluir este espectador? Esta ação é irreversível!') ]),
                                View::component(Label::class, label: 'ID', labelBold: true, children: [ View::text($this->spec->id) ] ),
                                View::component(Label::class, label: 'Nome', labelBold: true, children: [ View::text($this->spec->title) ] ),
                                View::component(Label::class, label: 'E-mail', labelBold: true, children: [ View::text($this->spec->email) ] ),
                                View::component(Label::class, label: 'Data de inscrição', labelBold: true, children: [ View::text(date_create($this->spec->subscription_datetime)->format('d/m/Y H:i:s')) ] )
                            ])
        ]: 
        null;
    }
}