<?php
namespace Congress\App\Admin\Panel\ManageAssessors;

require_once "Home.php";
require_once "CreateAssessor.php";

return
[
	'/' => Home::class,
	'/index' => Home::class,
	'/create' => CreateAssessor::class,
	'/[id]' => fn() => require_once __DIR__ . '/AssId/ns.php'
];