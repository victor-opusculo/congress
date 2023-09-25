<?php

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Articles\ArticleStatus;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Mail\NotificationDisapprovedArticle;
use Congress\Lib\Model\Mail\NotificationToUploadIddedArticle;
use Congress\Lib\Model\Submitters\Submitter;

require_once __DIR__ . '/../../../vendor/autoload.php';

session_name("congress_assessor_user");
session_start();

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
$action = $_POST['radAction'];
if (isset($_SESSION['assessor_id']) && $id)
{
    $messages = [];
    $conn = Connection::create();
    try
    {
        $article = (new Article([ 'id' => $id ]))->getSingle($conn);

        if ($article->idded_filename && $action == 'disapprove')
            throw new Exception('Não é possível reprovar após a versão identificada ter sido anexa!');

        if ($article->idded_filename && $action == 'approve')
            throw new Exception('Não é possível alterar status após a versão identificada ter sido anexa!');

        if ($article->evaluator_assessor_id != $_SESSION['assessor_id'])
            throw new Exception('Avaliador diferente do que aceitou o artigo!');

        $article->status = match ($action)
        {
            'approve' => ArticleStatus::Approved->value,
            'disapprove' => ArticleStatus::Disapproved->value,
            default => ArticleStatus::WaitingEvaluation->value
        };

        $article->is_approved = match ($action)
        {
            'approve' => 1,
            'disapprove' => 0,
            default => 0
        };

        $article->evaluator_feedback = $_POST['articles:txtFeedbackMessage'];
        
        $result = $article->save($conn);
        if ($result['affectedRows'] > 0)
        {
            $messages[] = 'Você avaliou o artigo com sucesso!';
            if ($action === 'approve')
            {
                $submitter = (new Submitter([ 'id' => $article->submitter_id ]))->getSingle($conn);
                NotificationToUploadIddedArticle::send($article, $submitter);
            }
            else if ($action === 'disapprove')
            {
                $submitter = (new Submitter([ 'id' => $article->submitter_id ]))->getSingle($conn);
                NotificationDisapprovedArticle::send($article, $submitter);
            }
        }
        else
            throw new Exception('Status de avaliação não alterado.');
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
    }
    finally { $conn->close(); }
    header('location:' . URLGenerator::generatePageUrl('assessor/panel/accepted_articles', [ 'messages' => implode('//', $messages) ]), true, 303);
}