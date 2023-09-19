<?php
namespace Congress\App\Assessor\Panel;

use Congress\Components\Panels\ButtonsContainer;
use Congress\Components\Panels\FeatureButton;
use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component, HeadManager};

class PanelHome extends Component
{

    protected function setUp()
    {
		HeadManager::$title = "Painel do Avaliador";
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::component(ButtonsContainer::class, children:
            [
                View::component(FeatureButton::class, label: 'Configurações de Conta', url: URLGenerator::generatePageUrl('/assessor/panel/settings'), iconUrl: URLGenerator::generateFileUrl('assets/pics/gear.svg') )
            ])
        ];
    }
}