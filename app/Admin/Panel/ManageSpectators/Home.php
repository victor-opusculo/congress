<?php
namespace Congress\App\Admin\Panel\ManageSpectators;

use Congress\Components\Data\BasicSearchInput;
use Congress\Components\Data\DataGrid;
use Congress\Components\Data\OrderByLinks;
use Congress\Components\Data\Paginator;
use Congress\Components\Link;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Spectators\SpectatorSubscription;
use Congress\Lib\Model\Database\Connection;
use PComp\{View, Component, Context, HeadManager};

class Home extends Component
{
    private const RESULTS_ON_PAGE = 20;

    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador: Espectadores";

        $conn = Connection::get();

        try
        {
            $getter = new SpectatorSubscription();
            $getter->setCryptKey(Connection::getCryptoKey());
            $this->specCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $specs = $getter->getMultiple($conn, $_GET['q'] ?? '', $_GET['order_by'] ?? '', (int)($_GET['page_num'] ?? 1), self::RESULTS_ON_PAGE);
            $this->specList = Data::transformDataRows($specs, 
            [
                'ID' => fn($a) => $a->id,
                'Título' => fn($a) => Data::truncateText($a->name, 100),
                'E-mail' => fn($a) => $a->email,
                'Área de interesse' => fn($a) => $a->data_json->areaOfInterest ?? '',
                'Data de inscrição' => fn($a) => date_create($a->subscription_datetime)->format('d/m/Y H:i:s')
            ]);
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    } 

    private int $specCount;
    private array $specList;

    protected function markup() : Component|array
    {
        return
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Espectadores inscritos'),
            View::component(BasicSearchInput::class),

            View::component(OrderByLinks::class, linksDefinitions: [ 'ID' => 'id', 'Nome' => 'name', 'E-mail' => 'email', 'Data de inscrição' => 'subscription_datetime' ]),
        
            View::component(DataGrid::class, 
                            dataRows: $this->specList, 
                            rudButtonsFunctionParamName: 'ID',
                            detailsButtonURL: URLGenerator::generatePageUrl('/admin/panel/man_spectators/{param}'),
                            deleteButtonURL: URLGenerator::generatePageUrl('/admin/panel/man_spectators/{param}/delete')
            ),
            View::component(Paginator::class, pageNum: $_GET['page_num'] ?? 1, numResultsOnPage: self::RESULTS_ON_PAGE, totalItems: $this->specCount),

            View::tag('div', class: 'text-right mt-4', children:
            [
                View::component(Link::class, class: 'btn mr-2', url: URLGenerator::generateScriptUrl('admin/man_spectators/export_to_csv.php', [ 'q' => $_GET['q'] ?? '', 'order_by' => $_GET['order_by'] ?? '' ]),
                    children:
                    [
                        View::text('Exportar para CSV')
                    ])
            ])
        ];
    }
}