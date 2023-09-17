<?php
namespace Congress\App\Submitter\Panel;

use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component};

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
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::tag('div', children:
            [
                View::tag('div', children: $this->children)
            ])
        ];
    }
}