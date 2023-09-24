<?php
namespace Congress\App\Submitter\Panel;

use Congress\App\Submitter\Panel\Layout;
use Congress\App\Submitter\Panel\PanelHome;
use Congress\App\Submitter\Panel\Settings;

require_once "PanelHome.php";
require_once "Layout.php";
require_once "Settings.php";

return
[
	'/settings' => Settings::class,
	'/my_articles' => fn() => require_once __DIR__ . '/MyArticles/ns.php',
	'/' => PanelHome::class,
	'/index' => PanelHome::class,
	'__layout' => Layout::class
];