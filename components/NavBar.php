<?php
namespace Congress\Components;

use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, StyleManager, Component};

require_once "Link.php";

class NavBar extends Component
{
    protected function setUp()
    {
       
    } 

    protected function markup() : Component
    {
        return View::tag('nav',
        class: 'sticky z-10 top-0 bg-green-600 text-white font-bold text-xl flex flex-row justify-center',
        children:
        [
            View::component(NavBarItem::class, url: URLGenerator::generatePageUrl('/'), label: 'Home'),
            View::component(NavBarItem::class, url: URLGenerator::generatePageUrl('/#secThemeGroups'), label: 'Grupos temáticos'),
            View::component(NavBarItem::class, url: URLGenerator::generatePageUrl('/#secArticleSubmission'), label: 'Submissões')
        ]);
    }
}