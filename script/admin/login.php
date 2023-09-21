<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Settings\AdminPassword;
use Congress\Lib\Model\Database\Connection;

require_once "../../vendor/autoload.php";

$messages = [];
if (isset($_POST['txtPassword']))
{
    $conn = Connection::create();
    try
    {
        $adminPass = (new AdminPassword())->getSingle($conn);

        if ($adminPass->verifyPassword($_POST['txtPassword']))
        {
            $_SESSION['admin_passhash'] = $adminPass->value;
            header('location:' . URLGenerator::generatePageUrl('/admin/panel'));
            exit;
        }
        else
            throw new Exception('Senha incorreta!');
    }
    catch (\Exception $e)
    {
        $messages[] = $e->getMessage();
        unset($_SESSION['admin_passhash']);
        session_unset();
		session_destroy();
    }
}
else
{
    unset($_SESSION['admin_passhash']);
    session_unset();
    session_destroy();
}

header('location:' . URLGenerator::generatePageUrl('/admin/login', [ 'messages' => implode('//', $messages) ]));

