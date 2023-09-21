<?php
namespace Congress\App\Admin\Panel\ManageAssessors;

require_once "Home.php";
require_once "ViewAssessor.php";

return
[
	'/' => Home::class,
	'/index' => Home::class,
	'/[id]' => ViewAssessor::class
];