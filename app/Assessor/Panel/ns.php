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
	'__layout' => Layout::class
];