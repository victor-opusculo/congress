<?php
namespace Congress\App;

use PComp\{View, Component};

require_once __DIR__ . "/../components/NavBar.php";

class Error extends Component
{
    protected \Exception $exception;

    protected function setUp()
    {
        
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::tag('article', class: 'mx-auto max-w-[600px] min-w-[300px] bg-red-300 border border-red-700 text-xl text-center p-4 mt-4', children:
            [
                View::tag('div', class: 'text-4xl my-2', children: [ View::text("❌") ]),
                View::text("Erro: " . $this->exception->getMessage())
            ])
        ];
    }
}