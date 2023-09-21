<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;

require_once "../../../vendor/autoload.php";

$messages = [];
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0; 

if (isset($_SESSION['admin_passhash']))
{
    $conn = Connection::create();
    try
    {
        $ass = (new Assessor([ 'id' => $id ]))->getSingle($conn);
        $ass->fillPropertiesFromFormInput($_POST);

        if ($_POST['txtNewPassword'] && mb_strlen($_POST['txtNewPassword']) > 0)
            $ass->changePassword($_POST['txtNewPassword']);

        $result = $ass->save($conn);
        if ($result['affectedRows'] > 0)
            $messages[] = "Dados salvos com sucesso!";
        else
            throw new Exception("Nenhum dado alterado.");
    }
    catch (\Exception $e)
    {
        $messages[] = $e->getMessage();
    }
}

header('location:' . URLGenerator::generatePageUrl("/admin/panel/man_assessors/$id/edit", [ 'messages' => implode('//', $messages) ]));

