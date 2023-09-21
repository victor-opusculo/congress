<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Submitters\Submitter;
use Congress\Lib\Model\Database\Connection;

require_once "../../../vendor/autoload.php";

$messages = [];
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0; 

if (isset($_SESSION['admin_passhash']))
{
    $conn = Connection::create();
    try
    {
        $sub = (new Submitter([ 'id' => $id ]))->getSingle($conn);
        $sub->fillPropertiesFromFormInput($_POST);

        if ($_POST['txtNewPassword'] && mb_strlen($_POST['txtNewPassword']) > 0)
            $sub->changePassword($_POST['txtNewPassword']);

        $result = $sub->save($conn);
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

header('location:' . URLGenerator::generatePageUrl("/admin/panel/man_submitters/$id/edit", [ 'messages' => implode('//', $messages) ]));

