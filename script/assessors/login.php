<?php
session_name("congress_assessor_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;

require_once "../../vendor/autoload.php";

$messages = [];
if (isset($_POST['txtEmail'], $_POST['txtPassword']))
{
    $conn = Connection::create();
    try
    {
        $submitter = (new Assessor([ 'email' => $_POST['txtEmail'] ]))->getSingleByEmail($conn);

        if ($submitter->verifyPassword($_POST['txtPassword']))
        {
            $_SESSION['assessor_id'] = $submitter->id;
            header('location:' . URLGenerator::generatePageUrl('/assessor/panel'));
            exit;
        }
        else
            throw new Exception('Senha incorreta!');
    }
    catch (\Exception $e)
    {
        $messages[] = $e->getMessage();
        unset($_SESSION['assessor_id']);
        session_unset();
		session_destroy();
    }
}
else
{
    unset($_SESSION['assessor_id']);
    session_unset();
    session_destroy();
}

header('location:' . URLGenerator::generatePageUrl('/assessor/login', [ 'messages' => implode('//', $messages) ]));

