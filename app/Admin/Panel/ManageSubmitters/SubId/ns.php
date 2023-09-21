<?php
namespace Congress\App\Admin\Panel\ManageSubmitters\SubId;

require_once "ViewSubmitter.php";
require_once "EditSubmitter.php";
require_once "DeleteSubmitter.php";

return
[
	'/' => ViewSubmitter::class,
	'/index' => ViewSubmitter::class,
	'/edit' => EditSubmitter::class,
	'/delete' => DeleteSubmitter::class
];