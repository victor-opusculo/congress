<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;

require_once "../../../vendor/autoload.php";

$messages = [];

if (isset($_SESSION['admin_passhash']))
{
    $conn = Connection::create();
    try
    {
        $ass = new Assessor();
        $ass->fillPropertiesFromFormInput($_POST);

        if ($ass->checkForExistentEmail($conn))
            throw new Exception('E-mail já existente para outro avaliador!');

        $ass->changePassword($_POST['txtPassword']);

        $result = $ass->save($conn);
        if ($result['affectedRows'] > 0)
            $messages[] = "Dados salvos com sucesso!";
        else
            throw new Exception("Avaliador não criado!");
    }
    catch (\Exception $e)
    {
        $messages[] = $e->getMessage();
    }
}

header('location:' . URLGenerator::generatePageUrl("/admin/panel/man_assessors", [ 'messages' => implode('//', $messages) ]));

