<?php
session_name("congress_submitter_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Submitters\Submitter;
use Congress\Lib\Model\Database\Connection;

require_once "../../vendor/autoload.php";

$messages = [];
if (isset($_SESSION['submitter_id']))
{
    $conn = Connection::create();
    try
    {
        unset($_POST['submitters:hidSubmitterId']);
        $submitter = (new Submitter([ 'id' => $_SESSION['submitter_id'] ]))->getSingle($conn);
        $submitter->fillPropertiesFromFormInput($_POST);

        if ($submitter->checkForExistentEmail($conn))
            throw new Exception('E-mail já utilizado por outro autor.');

        if ($_POST['txtOldPassword'])
        {
            if ($submitter->verifyPassword($_POST['txtOldPassword']))
            {
                $submitter->changePassword($_POST['txtNewPassword']);
            }
            else
                throw new Exception('Senha atual incorreta!');
        }

        $result = $submitter->save($conn);
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

header('location:' . URLGenerator::generatePageUrl('/submitter/panel/settings', [ 'messages' => implode('//', $messages) ]));

