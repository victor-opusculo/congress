<?php
namespace Congress\App\Admin\Panel\ManageAssessors;

use Congress\Components\Data\BasicSearchInput;
use Congress\Components\Data\DataGrid;
use Congress\Components\Data\OrderByLinks;
use Congress\Components\Data\Paginator;
use Congress\Components\Panels\ButtonsContainer;
use Congress\Components\Panels\FeatureButton;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;
use PComp\{View, Component, Context, HeadManager};

class Home extends Component
{
    private const RESULTS_ON_PAGE = 20;

    protected function setUp()
    {
		HeadManager::$title = "Painel do Avaliador: Gerenciar avaliadores";

        $conn = Connection::get();

        try
        {
            $assessorsGetter = new Assessor();
            $this->assessorCount = $assessorsGetter->getCount($conn, $_GET['q'] ?? '');
            $ass = $assessorsGetter->getMultiple($conn, $_GET['q'] ?? '', $_GET['order_by'] ?? '', (int)($_GET['page_num'] ?? 1), self::RESULTS_ON_PAGE);
            $this->assessorsList = Data::transformDataRows($ass, 
            [
                'ID' => fn($a) => $a->id,
                'Nome' => fn($a) => $a->name,
                'E-mail' => fn($a) => $a->email
            ]);
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    } 

    private int $assessorCount;
    private array $assessorsList;

    protected function markup() : Component|array
    {
        return
        [
            View::component(PageTitle::class, text: 'Gerenciar Avaliadores'),
            View::component(BasicSearchInput::class),
            View::component(OrderByLinks::class, linksDefinitions: [ 'ID' => 'id', 'Nome' => 'name', 'E-mail' => 'email' ]),
            View::component(DataGrid::class, 
                            dataRows: $this->assessorsList, 
                            rudButtonsFunctionParamName: 'ID', 
                            detailsButtonURL: URLGenerator::generatePageUrl('/admin/panel/man_assessors/{param}'),
                            editButtonURL: URLGenerator::generatePageUrl('/admin/panel/man_assessors/{param}/edit'),
                            deleteButtonURL: URLGenerator::generatePageUrl('/admin/panel/man_assessors/{param}/delete')
            ),
            View::component(Paginator::class, pageNum: $_GET['page_num'] ?? 1, numResultsOnPage: self::RESULTS_ON_PAGE, totalItems: $this->assessorCount)
        ];
    }
}