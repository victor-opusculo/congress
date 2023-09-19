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
	'/' => PanelHome::class,
	'/index' => PanelHome::class,
	'__layout' => Layout::class
];