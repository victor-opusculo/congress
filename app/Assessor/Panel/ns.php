<?php
namespace Congress\App\Assessor\Panel;

use Congress\App\Assessor\Panel\Layout;
use Congress\App\Assessor\Panel\PanelHome;
use Congress\App\Assessor\Panel\Settings;

require_once "PanelHome.php";
require_once "Layout.php";
require_once "Settings.php";

return
[
	'/settings' => Settings::class,
	'/' => PanelHome::class,
	'/index' => PanelHome::class,
	'/available_articles' => fn() => require_once __DIR__ . '/AvailableArticles/ns.php',
	'/accepted_articles' => fn() => require_once __DIR__ . '/AcceptedArticles/ns.php',
	'__layout' => Layout::class
];