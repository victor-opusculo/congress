<?php
namespace Congress\App\Submitter\Panel\MyArticles;

use Congress\Components\Data\BasicSearchInput;
use Congress\Components\Data\DataGrid;
use Congress\Components\Data\OrderByLinks;
use Congress\Components\Data\Paginator;
use Congress\Components\Link;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Submitters\Submitter;
use Congress\Lib\Model\Database\Connection;
use DateTimeZone;
use PComp\{View, Component, Context, HeadManager};

class Home extends Component
{
    private const RESULTS_ON_PAGE = 20;

    protected function setUp()
    {
		HeadManager::$title = "Painel do Autor: Meus artigos";

        $conn = Connection::get();

        try
        {
            $submitterId = ((int)$_SESSION['submitter_id'] ?? null);
            $getter = new Article([ 'submitter_id' => $submitterId ]);
            $this->artCount = $getter->getCount($conn, $_GET['q'] ?? '', '', $submitterId, null);
            $ass = $getter->getMultiple($conn, $_GET['q'] ?? '', '', $_GET['order_by'] ?? '', (int)($_GET['page_num'] ?? 1), self::RESULTS_ON_PAGE, $submitterId);
            $this->artList = Data::transformDataRows($ass, 
            [
                'id' => fn($a) => $a->id,
                'Título' => fn($a) => Data::truncateText($a->title, 100),
                'Status' => fn($a) => $a->translateStatus(),
                'Data de envio' => fn($a) => date_create($a->submitted_at)->format('d/m/Y H:i:s')
            ]);
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    } 

    private int $artCount;
    private array $artList;

    protected function markup() : Component|array
    {
        return
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Meus Artigos'),
            View::component(BasicSearchInput::class),
            View::component(OrderByLinks::class, linksDefinitions: [ 'ID' => 'id', 'Título' => 'title', 'Status' => 'status', 'Data de envio' => 'submitted_at' ]),
            View::tag('div', class: 'text-left my-2', children: [ View::component(Link::class, class: 'btn', url: URLGenerator::generatePageUrl('/submitter/panel/my_articles/create'), children: [ View::text('Cadastrar novo') ]) ]),
            View::component(DataGrid::class, 
                            dataRows: $this->artList, 
                            rudButtonsFunctionParamName: 'id',
                            columnsToHide: [ 'id' ], 
                            detailsButtonURL: URLGenerator::generatePageUrl('/submitter/panel/my_articles/{param}')
            ),
            View::component(Paginator::class, pageNum: $_GET['page_num'] ?? 1, numResultsOnPage: self::RESULTS_ON_PAGE, totalItems: $this->artCount)
        ];
    }
}