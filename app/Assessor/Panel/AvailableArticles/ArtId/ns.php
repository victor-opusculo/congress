<?php
namespace Congress\App\Assessor\Panel\AvailableArticles\ArtId;

require_once "ViewArticle.php";

return
[
	'/' => ViewArticle::class,
	'/index' => ViewArticle::class,
];