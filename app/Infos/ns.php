<?php
namespace Congress\App\Infos;

require_once "SubmissionRules.php";

return 
[
    '/' => SubmissionRules::class,
    '/index' => SubmissionRules::class
];