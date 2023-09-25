<?php
namespace Congress\App\Admin\Panel\ManageArticles;

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
		HeadManager::$title = "Painel do Administrador: Artigos";

        $conn = Connection::get();

        try
        {
            $status = $_GET['status'] ?? '';
            $getter = new Article();
            $this->artCount = $getter->getCount($conn, $_GET['q'] ?? '', $status, null, null);
            $art = $getter->getMultiple($conn, $_GET['q'] ?? '', $status, $_GET['order_by'] ?? '', (int)($_GET['page_num'] ?? 1), self::RESULTS_ON_PAGE);
            $this->artList = Data::transformDataRows($art, 
            [
                'ID' => fn($a) => $a->id,
                'Título' => fn($a) => Data::truncateText($a->title, 100),
                'Status' => fn($a) => $a->translateStatus(),
                'Avaliador' => fn($a) => $a->evaluator_assessor_id ? $a->getOtherProperties()->assessorName : 'Nenhum ainda',
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
            View::component(PageTitle::class, tag: 'h2', text: 'Artigos'),
            View::component(BasicSearchInput::class),

            View::tag('div', class: 'text-right my-2', children:
            [
                View::tag('label', children: 
                [
                    View::text('Somente com status: '),
                    View::tag('select', name: 'status', onchange: 'window.location.href = event.target.value;', children:
                    [
                        View::tag('option', value: URLGenerator::generatePageUrl('/admin/panel/man_articles'), children: [ View::text("Todos") ] ),
                        ...array_map( fn($case) => 
                            isset($_GET['status']) && $_GET['status'] == $case->value ? 
                                View::tag('option', selected: 'sel', value: URLGenerator::generatePageUrl('/admin/panel/man_articles', [ 'status' => $case->value ]), children: [ View::text(ArticleStatus::translate($case)) ] )
                                :
                                View::tag('option',  value: URLGenerator::generatePageUrl('/admin/panel/man_articles', [ 'status' => $case->value ]), children: [ View::text(ArticleStatus::translate($case)) ] )
                        , ArticleStatus::cases())
                    ])
                ])
            ]),

            View::component(OrderByLinks::class, linksDefinitions: [ 'ID' => 'id', 'Título' => 'title', 'Status' => 'status', 'Data de envio' => 'submitted_at' ]),
        
            View::component(DataGrid::class, 
                            dataRows: $this->artList, 
                            rudButtonsFunctionParamName: 'ID',
                            detailsButtonURL: URLGenerator::generatePageUrl('/admin/panel/man_articles/{param}'),
                            deleteButtonURL: URLGenerator::generatePageUrl('/admin/panel/man_articles/{param}/delete')
            ),
            View::component(Paginator::class, pageNum: $_GET['page_num'] ?? 1, numResultsOnPage: self::RESULTS_ON_PAGE, totalItems: $this->artCount),

            View::tag('div', class: 'text-right mt-4', children:
            [
                View::component(Link::class, class: 'btn mr-2', url: URLGenerator::generateScriptUrl('admin/man_articles/export_not_idded_to_zip.php', [ 'q' => $_GET['q'] ?? '', 'status' => $_GET['status'] ?? '', 'order_by' => $_GET['order_by'] ?? '' ]),
                    children:
                    [
                        View::text('Exportar artigos (sem identificação) para ZIP')
                    ]),
                View::component(Link::class, class: 'btn', url: URLGenerator::generateScriptUrl('admin/man_articles/export_idded_to_zip.php', [ 'q' => $_GET['q'] ?? '', 'status' => $_GET['status'] ?? '', 'order_by' => $_GET['order_by'] ?? '' ]),
                    children:
                    [
                        View::text('Exportar artigos (com identificação) para ZIP')
                    ])
            ])
        ];
    }
}