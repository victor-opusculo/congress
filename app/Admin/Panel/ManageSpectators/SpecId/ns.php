<?php

namespace Congress\App\Admin\Panel\ManageSpectators\SpecId;

require_once "ViewSpectator.php";
require_once "DeleteSpectator.php";

return 
[
    '/' => ViewSpectator::class,
    '/index' => ViewSpectator::class,
    '/delete' => DeleteSpectator::class
];