<?php
namespace Congress\App\Admin\Panel\ManageSpectators\SpecId;

use Congress\Components\{Label, Site\PageTitle};
use Congress\Components\Panels\ConvenienceLinks;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Spectators\SpectatorSubscription;
use PComp\{Component, HeadManager, ScriptManager, View};

class ViewSpectator extends Component
{
    public function setUp()
    {
        HeadManager::$title = 'Painel do administrador: Ver espectador';
        $conn = Connection::get();
        $getter = (new SpectatorSubscription([ 'id' => $this->id ]));
        $getter->setCryptKey(Connection::getCryptoKey());
        $this->spec = $getter->getSingle($conn);
    }

    protected int $id;
    protected SpectatorSubscription $spec;

    protected function markup(): Component|array|null
    {
        return 
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Ver espectador'),
            View::component(Label::class, label: 'ID', labelBold: true, children: [ View::text($this->spec->id) ]),
            View::component(Label::class, label: 'Nome', labelBold: true, children: [ View::text($this->spec->name) ]),
            View::component(Label::class, label: 'E-mail', labelBold: true, children: [ View::text($this->spec->email) ]),
            View::component(Label::class, label: 'Telefone', labelBold: true, children: [ View::text($this->spec->data_json->telephone) ]),
            View::component(Label::class, label: 'Cidade', labelBold: true, children: [ View::text($this->spec->data_json->city . '/' . $this->spec->data_json->stateUf) ]),
            View::component(Label::class, label: 'Área de interesse', labelBold: true, children: [ View::text($this->spec->data_json->areaOfInterest ?? '') ]),
            View::component(Label::class, label: 'Deficiência', labelBold: true, children: [ View::text($this->spec->data_json->disabilityInfo ?? '') ]),
            View::component(Label::class, label: 'Data e hora de inscrição', labelBold: true, children: [ View::text(date_create($this->spec->subscription_datetime)->format('d/m/Y H:i:s')) ]),

            View::component(ConvenienceLinks::class, deleteUrl: URLGenerator::generatePageUrl("/admin/panel/man_spectators/{$this->spec->id}/delete"))
        ];
    }
}