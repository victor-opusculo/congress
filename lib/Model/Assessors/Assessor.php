<?php
namespace Congress\Lib\Model\Assessors;

use Congress\Lib\Model\DataEntity;
use Congress\Lib\Model\DataProperty;
use Congress\Lib\Model\SqlSelector; 
use Congress\Lib\Model\Exceptions\DatabaseEntityNotFound; 

use mysqli;

class Assessor extends DataEntity
{
    public function __construct($initialValues = null)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('hidAssessorId', null, DataProperty::MYSQL_INT),
            'email' => new DataProperty('txtEmail', null, DataProperty::MYSQL_STRING),
            'password_hash' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'name' => new DataProperty('txtName', null, DataProperty::MYSQL_STRING)
        ];

        $this->initializeWithValues($initialValues);
    }

    protected string $databaseTable = 'assessors';
    protected string $formFieldPrefixName = 'assessors';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        return new self($dataRow);
    }

    public function getSingleByEmail(mysqli $conn) : self
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('*')
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->databaseTable}.email = ?")
        ->addValue('s', $this->properties->email->getValue());

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound('Autor não encontrado!', $this->databaseTable);
    }

    public function getCount(mysqli $conn, string $searchKeywords) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector
            ->addWhereClause('MATCH (name, email) AGAINST (?)')
            ->addValue('s', $searchKeywords);
        }

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getMultiple(mysqli $conn, string $searchKeywords, string $orderBy, int $page, int $numResultsOnPage) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("{$this->databaseTable}.*")
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector
            ->addWhereClause('MATCH (name, email) AGAINST (?)')
            ->addValue('s', $searchKeywords);
        }

        switch ($orderBy)
        {
            case 'name': $selector->setOrderBy('name ASC');
            case 'email': $selector->setOrderBy('email ASC');
            case 'id': default: $selector->setOrderBy('id DESC');
        }

        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit('?,?');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([$this, 'newInstanceFromDataRow'], $drs);
    }

    public function checkForExistentEmail(mysqli $conn) : bool
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->databaseTable}.email = ? AND NOT {$this->databaseTable}.id = ?")
        ->addValue('s', $this->properties->email->getValue())
        ->addValue('i', $this->properties->id->getValue());

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return $count > 0;
    }

    public function verifyPassword(string $givenPassword) : bool
    {
        return password_verify($givenPassword, $this->properties->password_hash->getValue());
    }

    public function changePassword(string $newPassword)
    {
        $this->properties->password_hash->setValue(password_hash($newPassword, PASSWORD_DEFAULT));
    }
}