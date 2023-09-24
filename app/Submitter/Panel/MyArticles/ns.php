<?php
namespace Congress\App\Submitter\Panel\MyArticles;

require_once "Home.php";
require_once "Create.php";

return
[
	'/' => Home::class,
	'/index' => Home::class,
	'/create' => Create::class,
	'/[id]' => fn() => require_once __DIR__ . '/ArtId/ns.php'
];