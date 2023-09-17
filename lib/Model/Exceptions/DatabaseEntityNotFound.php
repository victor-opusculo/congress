<?php
namespace Congress\Lib\Model\Exceptions;

class DatabaseEntityNotFound extends \Exception
{
    public string $databaseTable;
    public function __construct($message, $dbTable)
    {
        parent::__construct($message);
        $this->databaseTable = $dbTable;
    }
}