<?php
namespace Congress\Lib\Model\Spectators;

use Congress\Lib\Model\DataEntity;
use Congress\Lib\Model\DataObjectProperty;
use Congress\Lib\Model\DataProperty;
use Congress\Lib\Model\SqlSelector;
use mysqli;

require_once __DIR__ . '/../../../vendor/autoload.php';

class SpectatorSubscription extends DataEntity
{
    public function __construct($initialData = null)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('id', null, DataProperty::MYSQL_INT, false),
            'name' => new DataProperty('txtName', null, DataProperty::MYSQL_STRING, true),
            'email' => new DataProperty('txtEmail', null, DataProperty::MYSQL_STRING, true),
            'data_json' => new DataObjectProperty((object)
            [
                'telephone' => new DataProperty('txtTelephone', 'Não informado'),
                'city' => new DataProperty('txtCity', 'Não informada'),
                'stateUf' => new DataProperty('txtStateUf', 'ND'),
                'areaOfInterest' => new DataProperty('txtAreaOfInterest', ''),
                'disabilityInfo' => new DataProperty('txtDisabilityInfo', '')
            ], true),
            'subscription_datetime' => new DataProperty('', date('Y-m-d H:i:s'), DataProperty::MYSQL_STRING, false)
        ];

        $this->initializeWithValues($initialData);
    }

    protected string $databaseTable = 'spectators_subscriptions';
    protected string $formFieldPrefixName = 'specSubscriptions';
    protected array $primaryKeys = ['id']; 

    protected function newInstanceFromDataRow($dataRow)
    {
        return new self($dataRow);
    }

    public function existsByEmail(mysqli $conn) : bool
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable)
        ->addWhereClause($this->getWhereQueryColumnName('email') . ' = ? ')
        ->addValue('s', $this->properties->email->getValue());

        $count = (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return $count > 0;
    }

    public function getCount(mysqli $conn, string $searchKeywords) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector
            ->addWhereClause($this->getWhereQueryColumnName('name') . ' LIKE ? OR ' . $this->getWhereQueryColumnName('email') . ' LIKE ? ')
            ->addValues('ss', ["%{$searchKeywords}%", "%{$searchKeywords}%"]);
        }

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getMultiple(mysqli $conn, string $searchKeywords, string $orderBy, ?int $page, ?int $numResultsOnPage) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->getSelectQueryColumnName('id'))
        ->addSelectColumn($this->getSelectQueryColumnName('email'))
        ->addSelectColumn($this->getSelectQueryColumnName('name'))
        ->addSelectColumn($this->getSelectQueryColumnName('data_json'))
        ->addSelectColumn($this->getSelectQueryColumnName('subscription_datetime'))
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector
            ->addWhereClause($this->getWhereQueryColumnName('name') . ' LIKE ? OR ' . $this->getWhereQueryColumnName('email') . ' LIKE ? ')
            ->addValues('ss', ["%{$searchKeywords}%", "%{$searchKeywords}%"]);
        }

        $selector->setOrderBy(match ($orderBy)
        {
            'name' => 'name ASC',
            'email' => 'email ASC',
            'subscription_datetime' => 'subscription_datetime DESC',
            'id' => 'id DESC',
            default => 'id DESC'
        });

        if (!empty($page) && !empty($numResultsOnPage))
        {
            $calc_page = ($page - 1) * $numResultsOnPage;
            $selector->setLimit('?,?');
            $selector->addValues('ii', [ $calc_page, $numResultsOnPage ]);
        }

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([$this, 'newInstanceFromDataRow'], $drs);
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $this->properties->subscription_datetime->setValue(date('Y-m-d H:i:s'));
        return 0;
    }
}