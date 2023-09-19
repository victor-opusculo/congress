<?php
namespace Congress\App\Assessor\Panel;

use Congress\Components\Link;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Assessors\Assessor;
use PComp\{View, Component};

class Layout extends Component
{
    protected function setUp()
    {
        session_name("congress_assessor_user");
        session_start();
        if (!isset($_SESSION['assessor_id']))
        {
            header('location:' . URLGenerator::generatePageUrl('/assessor/login', [ 'messages' => 'Sessão não iniciada!' ]));
            exit;
        }

        $assessor = (new Assessor([ 'id' => $_SESSION['assessor_id'] ]))->getSingle(Connection::get());
        $this->assessorName = $assessor->name;
    } 

    protected string $assessorName = '***';

    protected function markup() : Component|array
    {
        return
        [
            View::tag('div', class: 'm-2', children:
            [
                View::tag('h1', children: [ View::text('Painel do Avaliador') ]),
                View::tag('div', class: 'text-left my-2', children: 
                [ 
                    View::component(Link::class, class: 'link text-lg', url: URLGenerator::generatePageUrl('/assessor/panel'), children: [ View::rawText('&larr; Voltar para home do painel') ]) 
                ]),
                View::tag('section', class: 'flex flex-row justify-between p-2 my-2 border-b border-b-slate-700 items-end', children:
                [
                    View::tag('span', children: 
                    [
                        View::text('Você está logado(a) como avaliador(a): ' . $this->assessorName)
                    ]),
                    View::tag('a', class: 'btn', href: URLGenerator::generateScriptUrl('assessors/logout.php'), children: [ View::text('Sair') ] )
                ]),
                View::tag('div', children: $this->children)
            ])
        ];
    }
}