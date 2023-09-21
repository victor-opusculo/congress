<?php
namespace Congress\App\Admin\Panel\ManageAssessors\AssId;

require_once "ViewAssessor.php";
require_once "EditAssessor.php";
require_once "DeleteAssessor.php";

return
[
	'/' => ViewAssessor::class,
	'/index' => ViewAssessor::class,
	'/edit' => EditAssessor::class,
	'/delete' => DeleteAssessor::class
];