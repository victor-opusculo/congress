<?php
session_name("congress_submitter_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Database\Connection;

require_once "../../../vendor/autoload.php";

$messages = [];

if (isset($_SESSION['submitter_id']))
{
    $conn = Connection::create();
    try
    {
        $art = new Article([ 'submitter_id' => $_SESSION['submitter_id'] ]);
        $art->fillPropertiesFromFormInput($_POST, $_FILES);

        $result = $art->save($conn);
        if ($result['newId'])
            $messages[] = "Artigo enviado com sucesso!";
        else
            throw new Exception("Artigo não cadastrado!");
    }
    catch (\Exception $e)
    {
        $messages[] = $e->getMessage();
    }
}

header('location:' . URLGenerator::generatePageUrl("/submitter/panel/my_articles", [ 'messages' => implode('//', $messages) ]), true, 303);

