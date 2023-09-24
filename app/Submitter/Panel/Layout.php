<?php
namespace Congress\App\Submitter\Panel;

use Congress\Components\Link;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Submitters\Submitter;
use PComp\{View, Component, Context};

class Layout extends Component
{
    protected function setUp()
    {
        session_name("congress_submitter_user");
        session_start();
        if (!isset($_SESSION['submitter_id']))
        {
            header('location:' . URLGenerator::generatePageUrl('/submitter/login', [ 'messages' => 'Sessão não iniciada!' ]));
            exit;
        }

        $submitter = (new Submitter([ 'id' => $_SESSION['submitter_id'] ]))->getSingle(Connection::get());
        $this->submitterName = $submitter->name;

        Context::set('submitter_name', $submitter->name);
    } 

    protected string $submitterName = '***';

    protected function markup() : Component|array
    {
        return
        [
            View::tag('div', class: 'm-2', children:
            [
                View::tag('h1', children: [ View::text('Painel do Autor') ]),
                View::tag('div', class: 'text-left my-2', children: 
                [ 
                    View::component(Link::class, class: 'link text-lg', url: URLGenerator::generatePageUrl('/submitter/panel'), children: [ View::rawText('&larr; Voltar para home do painel') ]) 
                ]),
                View::tag('section', class: 'flex flex-row justify-between p-2 my-2 border-b border-b-slate-700 items-end', children:
                [
                    View::tag('span', children: 
                    [
                        View::text('Você está logado(a) como autor(a): ' . $this->submitterName)
                    ]),
                    View::tag('a', class: 'btn', href: URLGenerator::generateScriptUrl('submitters/logout.php'), children: [ View::text('Sair') ] )
                ]),
                View::tag('div', children: $this->children)
            ])
        ];
    }
}