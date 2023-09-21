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
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
        $ass = (new Assessor([ 'id' => $id ]))->getSingle($conn);

        $result = $ass->delete($conn);
        if ($result['affectedRows'] > 0)
            $messages[] = "Avaliador excluído com sucesso!";
        else
            throw new Exception("Avaliador não excluído!");
    }
    catch (\Exception $e)
    {
        $messages[] = $e->getMessage();
    }
}

header('location:' . URLGenerator::generatePageUrl("/admin/panel/man_assessors", [ 'messages' => implode('//', $messages) ]));

