<?php
namespace Congress\App\Assessor\Panel\AvailableArticles;

use Congress\Components\Data\BasicSearchInput;
use Congress\Components\Data\DataGrid;
use Congress\Components\Data\OrderByLinks;
use Congress\Components\Data\Paginator;
use Congress\Components\Link;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Articles\ArticleStatus;
use Congress\Lib\Model\Database\Connection;
use PComp\{View, Component, Context, HeadManager};

class Home extends Component
{
    private const RESULTS_ON_PAGE = 20;

    protected function setUp()
    {
		HeadManager::$title = "Painel do Avaliador: Artigos disponíveis para avaliação";

        $conn = Connection::get();

        try
        {
            $getter = new Article([ 'status' => ArticleStatus::WaitingAssessor ]);
            $this->artCount = $getter->getCount($conn, $_GET['q'] ?? '', ArticleStatus::WaitingAssessor->value, null, null);
            $ass = $getter->getMultiple($conn, $_GET['q'] ?? '', ArticleStatus::WaitingAssessor->value, $_GET['order_by'] ?? '', (int)($_GET['page_num'] ?? 1), self::RESULTS_ON_PAGE);
            $this->artList = Data::transformDataRows($ass, 
            [
                'ID' => fn($a) => $a->id,
                'Título' => fn($a) => Data::truncateText($a->title, 100),
                'Palavras-chave' => fn($a) => Data::truncateText($a->keywords, 100),
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
            View::component(PageTitle::class, tag: 'h2', text: 'Artigos Disponíveis'),
            View::component(BasicSearchInput::class),
            View::component(OrderByLinks::class, linksDefinitions: [ 'ID' => 'id', 'Título' => 'title', 'Data de envio' => 'submitted_at' ]),
            View::component(DataGrid::class, 
                            dataRows: $this->artList, 
                            rudButtonsFunctionParamName: 'ID', 
                            detailsButtonURL: URLGenerator::generatePageUrl('/assessor/panel/available_articles/{param}')
            ),
            View::component(Paginator::class, pageNum: $_GET['page_num'] ?? 1, numResultsOnPage: self::RESULTS_ON_PAGE, totalItems: $this->artCount)
        ];
    }
}