<?php
namespace Congress\App\Submitter;

require_once "Login.php";

return
[
	'/' => Login::class,
	'/login' => Login::class,
    '/panel' => fn() => require_once __DIR__ . '/Panel/ns.php'
    //fn() => require_once __DIR__ . '/PersonalPage/ns.php',
];