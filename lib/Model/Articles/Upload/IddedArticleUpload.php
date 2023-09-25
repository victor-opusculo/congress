<?php

namespace Congress\Lib\Model\Articles\Upload;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Congress\Lib\Model\FileUploadUtils;

final class IddedArticleUpload
{
    private function __construct() { }

    public const UPLOAD_DIR = 'uploads/articles/{articleId}/';
    public const ALLOWED_TYPES = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    public const MAX_SIZE = 10485760 /* 10MB */;

    /**
     * Processa o upload de arquivo de artigo.
     * @param int $articleId ID do artigo
     * @param array $filePostData Array $_FILES
     * @param string $fileInputElementName Nome do elemento do tipo file do formulário de upload
     * @return bool
     * @throws ArticleUploadException
     * */
    public static function uploadArticleFile(int $articleId, array $filePostData, string $fileInputElementName) : bool
    {
            $fullDir = str_replace("{articleId}", (string)$articleId, __DIR__ . "/../../../../" . self::UPLOAD_DIR);
            $fileName = basename($filePostData[$fileInputElementName]["name"]);
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uploadFile = $fullDir . 'idded.' . $fileExtension;
        
            FileUploadUtils::checkForUploadError($filePostData[$fileInputElementName], self::MAX_SIZE, [self::class, 'throwException'], [ $fileName, $articleId ], self::ALLOWED_TYPES);

            if (!is_dir($fullDir))
                mkdir($fullDir, 0777, true);
                    
            if (!file_exists($uploadFile))
            {
                if (move_uploaded_file($filePostData[$fileInputElementName]["tmp_name"], $uploadFile))
                    return true;
                else
                    self::throwException("Erro ao mover o arquivo após upload.", $fileName, $articleId);
            }
            else
                self::throwException("Arquivo enviado já existente no servidor.", $fileName, $articleId);
            
            return false;		
    }

    public static function getExtension(array $filePostData, string $fileInputElementName ) : string
    {
        $fileName = basename($filePostData[$fileInputElementName]["name"]);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        return $fileExtension;
    }

    public static function checkForUploadError(array $filePostData, string $fileInputElementName) : void
    {
        $fileName = basename($filePostData[$fileInputElementName]["name"]);
        FileUploadUtils::checkForUploadError($filePostData[$fileInputElementName], self::MAX_SIZE, [self::class, 'throwException'], [ $fileName, -1 ], self::ALLOWED_TYPES);
    }

    public static function deleteArticleFile(int $articleId, string $fileExtension) : bool
    {
        $locationFilePath = str_replace("{articleId}", (string)$articleId, __DIR__ . "/../../../../" . self::UPLOAD_DIR . 'not_idded.' . $fileExtension);
        
        if (file_exists($locationFilePath))
        {
            if(unlink($locationFilePath))
                return true;
            else
                self::throwException("Erro ao excluir o arquivo de artigo.", basename($locationFilePath), $articleId);
        }
        return false;
    }

    public static function cleanArticleFolder(int $articleId)
    {
        $fullDir = str_replace("{articleId}", (string)$articleId, __DIR__ . "/../../../../" . self::UPLOAD_DIR);
        
        if (is_dir($fullDir))
        {
            $files = glob($fullDir . "*"); // get all file names
            
            foreach($files as $file)
            {
                if(is_file($file)) 
                    unlink($file); // delete file
            }
        }
    }

    public static function checkForEmptyArticleDir(int $articleId)
    {
        $fullDir = str_replace("{articleId}", (string)$articleId, __DIR__ . "/../../../../" . self::UPLOAD_DIR);
        
        if (is_dir($fullDir))
            if (FileUploadUtils::isDirEmpty($fullDir))
                rmdir($fullDir);
    }

    public static function throwException(string $message, string $fileName, int $articleId)
    {
        throw new ArticleUploadException($message, $fileName, $articleId); 
    }
}
