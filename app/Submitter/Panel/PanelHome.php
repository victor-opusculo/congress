<?php
namespace Congress\App\Submitter\Panel;

use Congress\Components\Panels\ButtonsContainer;
use Congress\Components\Panels\FeatureButton;
use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component, HeadManager};

class PanelHome extends Component
{

    protected function setUp()
    {
		HeadManager::$title = "Painel do Autor";
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::component(ButtonsContainer::class, children:
            [
                View::component(FeatureButton::class, label: 'Configurações de Conta', url: URLGenerator::generatePageUrl('/submitter/panel/settings'), iconUrl: URLGenerator::generateFileUrl('assets/pics/gear.svg') ),
                View::component(FeatureButton::class, label: 'Meus Artigos', url: URLGenerator::generatePageUrl('/submitter/panel/my_articles'), iconUrl: URLGenerator::generateFileUrl('assets/pics/document.svg') )
            ])
        ];
    }
}