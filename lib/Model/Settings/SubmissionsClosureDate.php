<?php
namespace Congress\Lib\Model\Settings;

use Congress\Lib\Model\DataEntity;
use Congress\Lib\Model\DataProperty;

use mysqli;

class SubmissionsClosureDate extends DataEntity
{
    public function __construct($initialValues = null)
    {
        $this->properties = (object)
        [
            'name' => new DataProperty('', 'SUBMISSIONS_CLOSURE_DATE', DataProperty::MYSQL_STRING),
            'value' => new DataProperty('dateDate', null, DataProperty::MYSQL_STRING)
        ];

        $this->properties->name->setValue('SUBMISSIONS_CLOSURE_DATE');
        $this->initializeWithValues($initialValues);
    }

    protected string $databaseTable = 'settings';
    protected string $formFieldPrefixName = 'settingsSubmClosureDate';
    protected array $primaryKeys = ['name'];

    protected function newInstanceFromDataRow($dataRow)
    {
        return new self($dataRow);
    }
   
    public function passed(mysqli $conn) : bool
    {
        $sett = $this->getSingle($conn);
        $closure = date_create($sett->value);
        $today = date_create('now');

        return $today >= $closure;
    }
}