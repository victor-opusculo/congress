<?php
namespace Congress\Components\Site;

use PComp\{View, ScriptManager, Component};
use Congress\Lib\Helpers\URLGenerator;

class MainSlideShow extends Component
{
    protected array $slidesFiles;

    protected function setUp()
    {
        ScriptManager::registerScript("slideShowScript", file_get_contents(__DIR__ . '/slideShowScript.js'));
        $this->slidesFiles = array_map('basename', glob(__DIR__ . '/../../assets/main-slides/*.JPG'));
    } 

    protected function markup() : Component
    {
        return View::tag('div', class: 'slideShowContainer relative w-full h-full flex flex-row', children:
        [
            ...array_map( fn($i) => 
                View::tag('img', 
                    ...['data-index' => $i ],
                    class: ($i > 1 ? 'hidden' : 'block') . ' slideImage max-h-full max-w-full mx-auto', 
                    src: URLGenerator::generateFileUrl('assets/main-slides/' . $this->slidesFiles[$i - 1]),
                    ), 
                range(1, count($this->slidesFiles)) 
            ),

            View::tag('button', type: 'button', class: 'prevSlideButton text-[5rem] absolute flex items-center ml-4 cursor-pointer hover:text-neutral-300 left-0 top-0 bottom-0 text-white font-bold', children: [ View::text('⇦') ]),
            View::tag('button', type: 'button', class: 'nextSlideButton text-[5rem] absolute flex items-center mr-4 cursor-pointer hover:text-neutral-300 right-0 top-0 bottom-0 text-white font-bold -scale-x-100', children: [ View::text('⇦') ]),
        ]);
    }
}