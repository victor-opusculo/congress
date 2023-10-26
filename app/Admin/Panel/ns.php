<?php
namespace Congress\App\Admin\Panel;

use Congress\App\Admin\Panel\Layout;
use Congress\App\Admin\Panel\PanelHome;
use Congress\App\Admin\Panel\Settings;

require_once "PanelHome.php";
require_once "Layout.php";
require_once "Settings.php";

return
[
	'/settings' => Settings::class,
	'/man_assessors' => fn() => require_once __DIR__ . '/ManageAssessors/ns.php',
	'/man_submitters' => fn() => require_once __DIR__ . '/ManageSubmitters/ns.php',
	'/man_articles' => fn() => require_once __DIR__ . '/ManageArticles/ns.php',
	'/man_spectators' => fn() => require_once __DIR__ . '/ManageSpectators/ns.php',
	'/' => PanelHome::class,
	'/index' => PanelHome::class,
	'__layout' => Layout::class
];