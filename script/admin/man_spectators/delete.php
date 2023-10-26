<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Spectators\SpectatorSubscription;

require_once "../../../vendor/autoload.php";

$messages = [];

if (isset($_SESSION['admin_passhash']))
{
    $conn = Connection::create();
    try
    {
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
        $spec = (new SpectatorSubscription([ 'id' => $id ]))->getSingle($conn);

        $result = $spec->delete($conn);
        if ($result['affectedRows'] > 0)
            $messages[] = "Espectador excluído com sucesso!";
        else
            throw new Exception("Espectador não excluído!");
    }
    catch (\Exception $e)
    {
        $messages[] = $e->getMessage();
    }
}

header('location:' . URLGenerator::generatePageUrl("/admin/panel/man_spectators", [ 'messages' => implode('//', $messages) ]), true, 303);

