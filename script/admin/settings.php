<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Settings\AdminPassword;
use Congress\Lib\Model\Database\Connection;

require_once "../../vendor/autoload.php";

$messages = [];
if (isset($_SESSION['admin_passhash']))
{
    $conn = Connection::create();
    try
    {
        $adminPass = (new AdminPassword())->getSingle($conn);

        if ($_POST['txtOldPassword'])
        {
            if ($adminPass->verifyPassword($_POST['txtOldPassword']))
            {
                $adminPass->changePassword($_POST['txtNewPassword']);
            }
            else
                throw new Exception('Senha atual incorreta!');
        }

        $result = $adminPass->save($conn);
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

header('location:' . URLGenerator::generatePageUrl('/admin/panel/settings', [ 'messages' => implode('//', $messages) ]));

