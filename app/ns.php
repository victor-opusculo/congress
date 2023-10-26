<?php
namespace Congress\App;

require_once "HomePage.php";
require_once "Layout.php";
require_once "Error.php";

return
[
	'/' => HomePage::class,
	'/admin' => fn() => require_once __DIR__ . '/Admin/ns.php',
	'/submitter' => fn() => require_once __DIR__ . '/Submitter/ns.php',
	'/assessor' => fn() => require_once __DIR__ . '/Assessor/ns.php',
	'/infos' => fn() => require_once __DIR__ . '/Infos/ns.php',
	'/spectator' => fn() => require_once __DIR__ . '/Spectator/ns.php',
	'__layout' => Layout::class,
	'__error' => Error::class
];