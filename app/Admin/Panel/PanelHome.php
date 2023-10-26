<?php
namespace Congress\App\Admin\Panel;

use Congress\Components\Panels\ButtonsContainer;
use Congress\Components\Panels\FeatureButton;
use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component, HeadManager};

class PanelHome extends Component
{

    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador";
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::component(ButtonsContainer::class, children:
            [
                View::component(FeatureButton::class, label: 'Configurações de Conta', url: URLGenerator::generatePageUrl('/admin/panel/settings'), iconUrl: URLGenerator::generateFileUrl('assets/pics/gear.svg') ),
                View::component(FeatureButton::class, label: 'Gerenciar Avaliadores', url: URLGenerator::generatePageUrl('/admin/panel/man_assessors'), iconUrl: URLGenerator::generateFileUrl('assets/pics/users.svg') ),
                View::component(FeatureButton::class, label: 'Gerenciar Autores', url: URLGenerator::generatePageUrl('/admin/panel/man_submitters'), iconUrl: URLGenerator::generateFileUrl('assets/pics/users.svg') ),
                View::component(FeatureButton::class, label: 'Gerenciar Espectadores', url: URLGenerator::generatePageUrl('/admin/panel/man_spectators'), iconUrl: URLGenerator::generateFileUrl('assets/pics/users.svg') ),
                View::component(FeatureButton::class, label: 'Artigos', url: URLGenerator::generatePageUrl('/admin/panel/man_articles'), iconUrl: URLGenerator::generateFileUrl('assets/pics/document.svg') )
            ])
        ];
    }
}