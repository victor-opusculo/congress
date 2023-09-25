<?php
session_name("congress_submitter_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Database\Connection;

require_once "../../../vendor/autoload.php";

$messages = [];
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
if (isset($_SESSION['submitter_id']) && $id)
{
    $conn = Connection::create();
    try
    {
        $art = (new Article([ 'id' => $id, 'submitter_id' => $_SESSION['submitter_id'] ]))->getSingleFromSubmitter($conn);
        $art->fillPropertiesFromFormInput([], $_FILES);

        $result = $art->save($conn);
        if ($result['affectedRows'] > 0)
            $messages[] = "Artigo editado com sucesso!";
        else
            throw new Exception("Artigo não editado!");
    }
    catch (\Exception $e)
    {
        $messages[] = $e->getMessage();
    }
}

header('location:' . URLGenerator::generatePageUrl("/submitter/panel/my_articles/$id", [ 'messages' => implode('//', $messages) ]), true, 303);

