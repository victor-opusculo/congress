<?php
namespace Congress\App\Admin\Panel;

use Congress\Components\Link;
use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component};

class Layout extends Component
{
    protected function setUp()
    {
        session_name("congress_admin_user");
        session_start();
        if (!isset($_SESSION['admin_passhash']))
        {
            header('location:' . URLGenerator::generatePageUrl('/admin/login', [ 'messages' => 'Sessão não iniciada!' ]));
            exit;
        }
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::tag('div', class: 'm-2', children:
            [
                View::tag('h1', children: [ View::text('Painel do Administrador') ]),
                View::tag('div', class: 'text-left my-2', children: 
                [ 
                    View::component(Link::class, class: 'link text-lg', url: URLGenerator::generatePageUrl('/admin/panel'), children: [ View::rawText('&larr; Voltar para home do painel') ]) 
                ]),
                View::tag('section', class: 'flex flex-row justify-between p-2 my-2 border-b border-b-slate-700 items-end', children:
                [
                    View::tag('span', children: 
                    [
                        View::text('Você está logado(a) como administrador(a)')
                    ]),
                    View::tag('a', class: 'btn', href: URLGenerator::generateScriptUrl('admin/logout.php'), children: [ View::text('Sair') ] )
                ]),
                View::tag('div', children: $this->children)
            ])
        ];
    }
}