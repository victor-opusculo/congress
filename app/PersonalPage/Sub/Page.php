<?php

namespace App\PersonalPage\Sub;

use PComp\View;

class Page extends \PComp\Component
{		
	protected function markup() : \PComp\Component|array|null
	{
		return View::tag('h2', children: [ View::text("Show person 2") ]);
	}
}