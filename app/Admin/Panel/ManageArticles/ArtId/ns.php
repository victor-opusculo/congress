<?php

namespace Congress\App\Admin\Panel\ManageArticles\ArtId;

require_once "ViewArticle.php";
require_once "DeleteArticle.php";

return 
[
    '/' => ViewArticle::class,
    '/index' => ViewArticle::class,
    '/delete' => DeleteArticle::class
];