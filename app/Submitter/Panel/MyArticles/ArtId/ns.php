<?php
namespace Congress\App\Submitter\Panel\MyArticles\ArtId;

require_once "ViewArticle.php";

return
[
	'/' => ViewArticle::class,
	'/index' => ViewArticle::class,
];