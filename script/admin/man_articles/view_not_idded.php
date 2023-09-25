<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Articles\Upload\NotIddedArticleUpload;

require_once __DIR__ . '/../../../vendor/autoload.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

if (isset($_SESSION['admin_passhash']))
{
    $conn = Connection::create();
    try
    {
        $article = (new Article([ 'id' => $id ]))->getSingle($conn);
        $filename = $article->no_idded_filename;
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        $fullPath = __DIR__ . '/../../../' . NotIddedArticleUpload::UPLOAD_DIR . $filename;
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