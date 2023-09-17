<?php 
namespace App\PersonalPage;

use PComp\View;

class Page extends \PComp\Component
{	
	protected function markup() : \PComp\Component|array|null
	{
		return View::tag('h1', children: [ View::text("Página pessoal.") ]);
	}
}