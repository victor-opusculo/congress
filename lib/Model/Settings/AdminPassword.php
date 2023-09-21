<?php
namespace Congress\Lib\Model\Settings;

use Congress\Lib\Model\DataEntity;
use Congress\Lib\Model\DataProperty;

use mysqli;

class AdminPassword extends DataEntity
{
    public function __construct($initialValues = null)
    {
        $this->properties = (object)
        [
            'name' => new DataProperty('', 'ADMIN_PASSWORD_HASH', DataProperty::MYSQL_STRING),
            'value' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];

        $this->properties->name->setValue('ADMIN_PASSWORD_HASH');
        $this->initializeWithValues($initialValues);
    }

    protected string $databaseTable = 'settings';
    protected string $formFieldPrefixName = 'settingsAdminPassword';
    protected array $primaryKeys = ['name'];

    protected function newInstanceFromDataRow($dataRow)
    {
        return new self($dataRow);
    }
   
    public function verifyPassword(string $givenPassword) : bool
    {
        return password_verify($givenPassword, $this->properties->value->getValue());
    }

    public function changePassword(string $newPassword)
    {
        $this->properties->value->setValue(password_hash($newPassword, PASSWORD_DEFAULT));
    }
}