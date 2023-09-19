<?php
session_name("congress_assessor_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;

require_once "../../vendor/autoload.php";

$messages = [];
if (isset($_SESSION['assessor_id']))
{
    $conn = Connection::create();
    try
    {
        unset($_POST['assessors:hidAssessorId']);
        $assessor = (new Assessor([ 'id' => $_SESSION['assessor_id'] ]))->getSingle($conn);
        $assessor->fillPropertiesFromFormInput($_POST);

        if ($assessor->checkForExistentEmail($conn))
            throw new Exception('E-mail já utilizado por outro avaliador.');

        if ($_POST['txtOldPassword'])
        {
            if ($assessor->verifyPassword($_POST['txtOldPassword']))
            {
                $assessor->changePassword($_POST['txtNewPassword']);
            }
            else
                throw new Exception('Senha atual incorreta!');
        }

        $result = $assessor->save($conn);
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

header('location:' . URLGenerator::generatePageUrl('/assessor/panel/settings', [ 'messages' => implode('//', $messages) ]));

