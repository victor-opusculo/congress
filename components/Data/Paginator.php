<?php
namespace Congress\Components\Data;

use Congress\Lib\Helpers\QueryString;
use PComp\Component;
use PComp\View;

class Paginator extends Component
{
    public function setUp()
    {
        $this->pageNum = isset($_GET['page_num']) && is_numeric($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    }

    protected int $totalItems;
    protected int $numResultsOnPage;
    protected int $pageNum;

    protected static function currQS() : string
    {
        return QueryString::getQueryStringForHtmlExcept('page_num');
    }

    protected static function addQSpagNum($pagNum) : string
    {
        return QueryString::formatNew('page_num', $pagNum, true);
    }

    protected function markup(): Component|array|null
    {
        if (ceil($this->totalItems / $this->numResultsOnPage) > 0)
            return View::tag('ul', class: 'pagination', children: 
            [
                $this->pageNum > 1 ? View::tag('li', class: 'prev', children: [ View::tag('a', href: "?" . self::currQS() . self::addQSpagNum($this->pageNum - 1), children: [ View::text('Anterior') ]) ] ) : null,
                $this->pageNum > 3 ?
                [
                    View::tag('li', class: 'start', children: [ View::tag('a', href: "?" . self::currQS() . self::addQSpagNum(1), children: [ View::text('1') ]) ]),
                    View::tag('li', class: 'dots', children: [ View::text('...') ])
                ] : null,

                ($this->pageNum - 2) > 0 ? View::tag('li', children: [ View::tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum-2), children: [ View::text($this->pageNum - 2) ]) ] ) : null,
                ($this->pageNum - 1) > 0 ? View::tag('li', children: [ View::tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum-1), children: [ View::text($this->pageNum - 1) ]) ] ) : null,

                View::tag('li', class: 'currentPageNum', children: [ View::tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum), children: [ View::text($this->pageNum) ]) ] ),

                ($this->pageNum + 1) < (ceil($this->totalItems / $this->numResultsOnPage) + 1) ? View::tag('li', children: [ View::tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum + 1), children: [ View::text($this->pageNum + 1) ]) ] ) : null,
                ($this->pageNum + 2) < (ceil($this->totalItems / $this->numResultsOnPage) + 1) ? View::tag('li', children: [ View::tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum + 2), children: [ View::text($this->pageNum + 2) ]) ] ) : null,

                $this->pageNum < (ceil($this->totalItems / $this->numResultsOnPage) - 2) ?
                [
                    View::tag('li', class: 'dots', children: [ View::text('...') ]),
                    View::tag('li', class: 'end', children: [ View::tag('a', href: "?" . self::currQS() . self::addQSpagNum(ceil($this->totalItems / $this->numResultsOnPage)), children: [ View::text(ceil($this->totalItems / $this->numResultsOnPage)) ]) ])
                ] : null,
                $this->pageNum < (ceil($this->totalItems / $this->numResultsOnPage)) ? View::tag('li', class: 'next', children: [ View::tag('a', href: "?" . self::currQS() . self::addQSpagNum($this->pageNum + 1), children: [ View::text('Próxima') ]) ] ) : null
            ]);
        else
            return null;
    }
}