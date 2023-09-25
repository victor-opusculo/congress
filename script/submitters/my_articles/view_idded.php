<?php
session_name("congress_submitter_user");
session_start();

use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Articles\Upload\IddedArticleUpload;

require_once __DIR__ . '/../../../vendor/autoload.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

if (isset($_SESSION['submitter_id']))
{
    $conn = Connection::create();
    try
    {
        $article = (new Article([ 'id' => $id, 'submitter_id' => $_SESSION['submitter_id'] ]))->getSingleFromSubmitter($conn);
        $filename = $article->idded_filename;
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        $fullPath = __DIR__ . '/../../../' . IddedArticleUpload::UPLOAD_DIR . $filename;
        $fullPath = str_replace('{articleId}', $article->id, $fullPath);
        $mime = mime_content_type($fullPath);
        header('Content-Type:' . $mime);
        header("Content-Disposition: attachment; filename=\"Artigo ID {$article->id}.$extension\"");
        readfile($fullPath);
    }
    catch (Exception $e)
    {
        die($e->getMessage());
    }
}