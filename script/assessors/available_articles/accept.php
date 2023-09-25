<?php

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Articles\ArticleStatus;
use Congress\Lib\Model\Database\Connection;

require_once __DIR__ . '/../../../vendor/autoload.php';

session_name("congress_assessor_user");
session_start();

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
if (isset($_SESSION['assessor_id']) && $id)
{
    $messages = [];
    $conn = Connection::create();
    try
    {
        $article = (new Article([ 'id' => $id ]))->getSingle($conn);
        $article->evaluator_assessor_id = $_SESSION['assessor_id'];
        $article->status = ArticleStatus::WaitingEvaluation->value;
        
        $result = $article->save($conn);

        if ($result['affectedRows'] > 0)
            $messages[] = 'Artigo aceito para avaliação!';
        else
            throw new Exception('Não foi possível registrar o aceite de avaliação.');
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
    }
    finally { $conn->close(); }
    header('location:' . URLGenerator::generatePageUrl('assessor/panel/accepted_articles', [ 'messages' => implode('//', $messages) ]), true, 303);
}