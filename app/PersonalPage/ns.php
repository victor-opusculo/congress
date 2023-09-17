<?php
namespace App\PersonalPage;

require_once __DIR__ . '/Page.php';
require_once __DIR__ . '/Layout.php';

return 
[
	'/' => Page::class,
	'/index' => Page::class,
	'/[id]' => fn() => require_once __DIR__ . '/PersonId/ns.php',
	'/sub' => fn() => require_once __DIR__ . '/Sub/ns.php',
	'__layout' => Layout::class
];