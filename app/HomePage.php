<?php
namespace Congress\App;

use Congress\Components\Data\DataGrid;
use Congress\Components\Data\Paginator;
use Congress\Components\Link;
use Congress\Components\Panels\ButtonsContainer;
use Congress\Components\Site\MainSlideShow;
use Congress\Lib\Helpers\System;
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
		HeadManager::$title = System::eventName();
	}
		
	protected function markup() : Component|array
	{
        return
        [
			View::tag('section', 
						class: 'relative w-full bg-emerald-700 h-[500px] bg-no-repeat bg-right',
						style: "background-image: url('" . URLGenerator::generateFileUrl('assets/pics/reg-dev.webp') . "');", 
						children:
			[
				View::tag('div', class: 'absolute top-0 bottom-0 left-0 right-0 bg-emerald-700/60 p-2', children: 
				[
					View::tag('div', class: 'text-white font-bold flex flex-col items-center justify-center mx-auto min-w-[400px] max-w-[700px]' , children: 
					[
						View::tag('h1', children: [ View::text(System::eventName()) ]),
						View::tag('p', class: 'text-amber-400 text-left text-2xl', children: [ View::rawText('&#10140; Dias 7 e 8 de dezembro de 2023') ]),
						View::tag('p', class: 'text-amber-400 text-left text-2xl', children: [ View::rawText('&#10140; Submissões de 20 de outubro a 20 de novembro') ]),
						View::tag('p', class: 'text-orange-500 text-left text-2xl mt-4', children: 
						[ 
							View::tag('img', src: URLGenerator::generateFileUrl('assets/pics/location.svg'), class: 'h-8 w-5 inline-block mr-2'),
							View::text('Câmara Municipal de Itapevi')
						]),
						View::tag('p', class: 'text-orange-400 text-center text-xl', children:
						[
							View::text('Rua Arnaldo Sérgio Cordeiro das Neves, nº 80 - Vila Nova - Itapevi/SP')
						])
					])
				])
			]),
			View::tag('section', id: 'secThemeGroups', children: [ View::rawText(file_get_contents(__DIR__ . '/theme-groups.html')) ]),
			View::tag('section', id: 'secArticleSubmission', class: 'w-full bg-red-300 px-4 py-4', children: 
			[
				View::tag('h1', children: [ View::text('Submissão de artigos') ]),
				View::tag('div', class: 'flex lg:flex-row flex-col items-center justify-center', children: 
				[
					View::component(Link::class, 
						class: 'flex flex-col items-center justify-center w-40 h-40 border border-red-700 rounded hover:backdrop-brightness-75 mr-2 mb-2', 
						url: URLGenerator::generatePageUrl('/infos'), 
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
					View::component(Link::class, 
						class: 'flex flex-col items-center justify-center w-40 h-40 border border-red-700 rounded hover:backdrop-brightness-75 mr-2 mb-2', 
						url: URLGenerator::generatePageUrl('/submitter/register'), 
						children: 
						[ 
							View::scTag('img', class: 'block h-20 mb-2', src: URLGenerator::generateFileUrl('assets/pics/write.svg'), alt: 'Cadastrar-se como autor'),
							View::tag('div', class: 'text-center', children: [ View::text('Cadastrar-se como autor')  ]) 
						]),
				])
			]),

			View::tag('section', id: 'secArticleEvaluation', class: 'w-full bg-emerald-300 px-4 py-4', children: 
			[
				View::tag('h1', children: [ View::text('Avaliação de artigos') ]),
				View::component(ButtonsContainer::class, children: 
				[
					View::component(Link::class, 
						class: 'flex flex-col items-center justify-center w-40 h-40 border border-emerald-700 rounded hover:backdrop-brightness-75 mr-2 mb-2', 
						url: URLGenerator::generatePageUrl('/assessor/panel'), 
						children: 
						[ 
							View::scTag('img', class: 'block h-20 mb-2', src: URLGenerator::generateFileUrl('assets/pics/gear.svg'), alt: 'Painel do avaliador'),
							View::tag('div', class: 'text-center', children: [ View::text('Painel do avaliador')  ]) 
						]),
				])
			])
        ];
	}
}