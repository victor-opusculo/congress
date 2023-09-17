<?php
namespace Congress\App;

use Congress\Components\Data\DataGrid;
use Congress\Components\Data\Paginator;
use Congress\Components\Link;
use Congress\Components\Site\MainSlideShow;
use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component, HeadManager, Context};

require_once __DIR__ . "/../components/NavBar.php";

class HomePage extends Component
{
	protected string $name;
	protected int $numberOfPersons;
	protected string $pageTitle;

	protected string $userName;
	
	protected function setUp()
	{
		$this->name = "Victor Opusculo O. V. de Almeida";
		$this->numberOfPersons = mt_rand(1, 8);
		$this->pageTitle = "Gerando {$this->numberOfPersons} pessoas!";
		HeadManager::$title = "Congresso";

		$this->userName = Context::get('user') ?? 'indefinido';

	}
		
	protected function markup() : Component|array
	{
        return
        [
			View::tag('section', class: 'w-full bg-emerald-700 h-[500px] flex items-center justify-center', children:
			[
				View::component(MainSlideShow::class)
			]),
			View::tag('section', id: 'secThemeGroups', children: [ View::rawText(file_get_contents(__DIR__ . '/theme-groups.html')) ]),
			View::tag('section', id: 'secArticleSubmission', class: 'w-full bg-red-300 px-4 py-4', children: 
			[
				View::tag('h1', children: [ View::text('Submissão de artigos') ]),
				View::tag('div', class: 'flex lg:flex-row flex-col items-center justify-center', children: 
				[
					View::component(Link::class, 
						class: 'flex flex-col items-center justify-center w-40 h-40 border border-red-700 rounded hover:backdrop-brightness-75 mr-2 mb-2', 
						url: URLGenerator::generateFileUrl('assets/docs/normas-de-submissão.docx'), 
						children: 
						[ 
							View::scTag('img', class: 'block h-20 mb-2', src: URLGenerator::generateFileUrl('assets/pics/document.svg'), alt: 'Normas de submissão'),
							View::tag('div', class: 'text-center', children: [ View::text('Normas de submissão')  ]) 
						]),
					View::component(Link::class, 
						class: 'flex flex-col items-center justify-center w-40 h-40 border border-red-700 rounded hover:backdrop-brightness-75 mr-2 mb-2', 
						url: URLGenerator::generateFileUrl('assets/docs/template-com-identificacao.docx'), 
						children: 
						[ 
							View::scTag('img', class: 'block h-20 mb-2', src: URLGenerator::generateFileUrl('assets/pics/document.svg'), alt: 'Template com identificação'),
							View::tag('div', class: 'text-center', children: [ View::text('Template com identificação')  ]) 
						]),
					View::component(Link::class, 
						class: 'flex flex-col items-center justify-center w-40 h-40 border border-red-700 rounded hover:backdrop-brightness-75 mr-2 mb-2', 
						url: URLGenerator::generateFileUrl('assets/docs/template-sem-identificacao.docx'), 
						children: 
						[ 
							View::scTag('img', class: 'block h-20 mb-2', src: URLGenerator::generateFileUrl('assets/pics/document.svg'), alt: 'Template sem identificação'),
							View::tag('div', class: 'text-center', children: [ View::text('Template sem identificação')  ]) 
						]),
					View::component(Link::class, 
						class: 'flex flex-col items-center justify-center w-40 h-40 border border-red-700 rounded hover:backdrop-brightness-75 mr-2 mb-2', 
						url: URLGenerator::generatePageUrl('/submitter/panel'), 
						children: 
						[ 
							View::scTag('img', class: 'block h-20 mb-2', src: URLGenerator::generateFileUrl('assets/pics/gear.svg'), alt: 'Painel do autor'),
							View::tag('div', class: 'text-center', children: [ View::text('Painel do autor')  ]) 
						]),
					])
			])
        ];
	}
}