<?php
session_name("congress_submitter_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Submitters\Submitter;
use Congress\Lib\Model\Database\Connection;

require_once "../../vendor/autoload.php";

if (isset($_POST['txtEmail'], $_POST['txtPassword']))
{
    $conn = Connection::create();
    try
    {
        $submitter = (new Submitter([ 'email' => $_POST['txtEmail'] ]))->getSingleByEmail($conn);

        if ($submitter->verifyPassword($_POST['txtPassword']))
        {
            $_SESSION['submitter_id'] = $submitter->id;
            header('location:' . URLGenerator::generatePageUrl('submitters/panel'));
            exit;
        }
        else
            throw new Exception('Senha incorreta!');
    }
    catch (\Exception $e)
    {
        unset($_SESSION['submitter_id']);
        session_unset();
		session_destroy();
    }
}
else
{
    unset($_SESSION['submitter_id']);
    session_unset();
    session_destroy();
}

header('location:' . URLGenerator::generatePageUrl('submitters/login'));

