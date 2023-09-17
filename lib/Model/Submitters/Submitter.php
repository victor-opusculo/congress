<?php
namespace Congress\Lib\Model\Submitters;

use Congress\Lib\Model\DataEntity;
use Congress\Lib\Model\DataProperty;
use Congress\Lib\Model\Exceptions\DatabaseEntityNotFound;
use Congress\Lib\Model\SqlSelector;
use mysqli;

class Submitter extends DataEntity
{
    public function __construct($initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('hidSubmitterId', null, DataProperty::MYSQL_INT),
            'email' => new DataProperty('txtEmail', null, DataProperty::MYSQL_STRING),
            'password_hash' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'name' => new DataProperty('txtName', null, DataProperty::MYSQL_STRING)
        ];

        $this->initializeWithValues($initialValues);
    }

    protected string $databaseTable = 'submitters';
    protected string $formFieldPrefixName = 'submitters';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        return new self($dataRow);
    }

    public function getSingleByEmail(mysqli $conn) : self
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('*')
        ->addWhereClause("{$this->databaseTable}.email = ?")
        ->addValue('s', $this->properties->email->getValue());

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound('Autor não encontrado!', $this->databaseTable);
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