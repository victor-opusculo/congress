<?php
namespace Congress\Lib\Model\Assessors;

use Congress\Lib\Model\DataEntity;
use Congress\Lib\Model\DataProperty;

class Assessor extends DataEntity
{
    public function __construct($initialValues)
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

    protected string $databaseTable = 'traits';
    protected string $formFieldPrefixName = 'submitters';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        return new self($dataRow);
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