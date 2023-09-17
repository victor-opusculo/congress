<?php
namespace Congress\App\Submitter;

use Congress\App\Submitter\Panel\Layout;
use Congress\App\Submitter\Panel\PanelHome;

require_once "PanelHome.php";
require_once "Layout.php";

return
[
	'/' => PanelHome::class,
	'/index' => PanelHome::class,
	'__layout' => Layout::class
];