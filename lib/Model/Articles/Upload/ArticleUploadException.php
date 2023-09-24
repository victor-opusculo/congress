<?php
namespace Congress\Lib\Model\Articles\Upload;

use Exception;

class ArticleUploadException extends Exception
{
    public function __construct(string $message, public string $fileName, public int $articleId)
    {
        parent::__construct($message);
    }
}