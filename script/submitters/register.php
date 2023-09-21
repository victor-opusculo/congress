<?php
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Submitters\Submitter;

require_once '../../vendor/autoload.php';

$conn = Connection::create();
$messages = [];
try
{
    if (!isset($_POST['txtEmail'], $_POST['txtName'], $_POST['txtPassword']))
        throw new Exception('Dados incompletos! Cadastro não feito!');

    $sub = new Submitter();
    $sub->email = $_POST['txtEmail'];
    $sub->name = $_POST['txtName'];
    $sub->changePassword($_POST['txtPassword']);

    if ($sub->checkForExistentEmail($conn))
        throw new Exception('Endereço de e-mail já existente!');

    $result = $sub->save($conn);
    if ($result['newId'])
    {
        $messages[] = "Cadastro efetuado com sucesso! Você pode agora entrar em seu painel do autor.";
    }
    else
        throw new Exception('Não foi possível efetuar o cadastro!');
}
catch (Exception $e)
{
    $messages[] = $e->getMessage();
}
finally { $conn->close(); }

header('location:' . URLGenerator::generatePageUrl('/submitter/login', [ 'messages' => implode('//', $messages) ]));