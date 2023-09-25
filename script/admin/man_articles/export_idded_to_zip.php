<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\System;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Articles\Upload\IddedArticleUpload;

require_once __DIR__ . '/../../../vendor/autoload.php';

if (isset($_SESSION['admin_passhash']))
{
    $conn = Connection::create();
    $search = $_GET['q'] ?? '';
    $status = $_GET['status'] ?? '';
    $orderBy = $_GET['order_by'] ?? '';

    try
    {
        $arts = (new Article())->getMultipleForExport($conn, $search, $status, $orderBy);
        $fileZip = tempnam(sys_get_temp_dir(), 'zip');
        $zip = new ZipArchive();
        $zip->open($fileZip, ZipArchive::OVERWRITE);

        if (!empty($arts))
            foreach ($arts as $art)
            {
                if (!$art->idded_filename)
                    continue;

                $filePath = System::systemBaseDir() . '/' . IddedArticleUpload::UPLOAD_DIR . '/' . $art->idded_filename;
                $filePath = str_replace('{articleId}', $art->id, $filePath);
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $zip->addFile($filePath, "{$art->id}_{$art->title}.{$extension}");
            }
        else
            die("Não há artigos segundo o critério atual de pesquisa!");

        $zip->close();
        header('Content-Type: application/zip');
        header('Content-Length: ' . filesize($fileZip));
        header('Content-Disposition: filename="Artigos-identificados.zip"');

        readfile($fileZip);
        unlink($fileZip);

    }
    catch (Exception $e)
    {
        die($e->getMessage());
    }
}