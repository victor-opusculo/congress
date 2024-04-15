<?php
namespace Congress\Components\Site;

use PComp\{View, ScriptManager, Component};
use Congress\Lib\Helpers\URLGenerator;

class ProgSlideShow extends Component
{
    protected array $slidesFiles;

    protected function setUp()
    {
        ScriptManager::registerScript("slideShowScript", file_get_contents(__DIR__ . '/slideShowScript.js'));
        $this->slidesFiles = array_map('basename', glob(__DIR__ . '/../../assets/prog-slides/*.jpg'));
    } 

    protected function markup() : Component
    {
        return View::tag('div', class: 'slideShowContainer relative w-full h-full flex flex-row bg-indigo-300', children:
        [
            ...array_map( fn($i) => 
                View::scTag('img', 
                    ...['data-index' => $i ],
                    class: ($i > 1 ? 'hidden' : 'block') . ' slideImage max-h-[500px] max-w-full mx-auto', 
                    src: URLGenerator::generateFileUrl('assets/prog-slides/' . $this->slidesFiles[$i - 1]),
                    ), 
                range(1, count($this->slidesFiles)) 
            ),

            View::tag('button', type: 'button', class: 'prevSlideButton text-[3rem] absolute flex items-end ml-4 cursor-pointer hover:text-neutral-300 left-0 top-0 bottom-0 text-white font-bold', children: [ View::text('⇦') ]),
            View::tag('button', type: 'button', class: 'nextSlideButton text-[3rem] absolute flex items-end mr-4 cursor-pointer hover:text-neutral-300 right-0 top-0 bottom-0 text-white font-bold -scale-x-100', children: [ View::text('⇦') ]),
        ]);
    }
}