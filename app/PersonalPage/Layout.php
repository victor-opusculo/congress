<?php

namespace App\PersonalPage;

use PComp\View;

class Layout extends \PComp\Component
{
    protected function markup() : \PComp\Component|array|null
    {
        return View::tag('fieldset', children:
        [
            View::tag('legend', children: [ View::text("Grupo") ]),
            ...$this->children
        ]);
    }
}