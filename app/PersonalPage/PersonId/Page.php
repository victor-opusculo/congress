<?php

namespace App\PersonalPage\PersonId;

use PComp\View;

class Page extends \PComp\Component
{
	protected int $id;
	protected string $name;
	
	protected function markup() : \PComp\Component|array|null
	{
		return View::tag('h2', children: [ View::text("ID fornecido: " . $this->id) ]);
	}
}