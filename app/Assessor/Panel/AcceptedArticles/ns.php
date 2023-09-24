<?php
namespace Congress\App\Assessor\Panel\AcceptedArticles;

require_once "Home.php";

return 
[
    '/' => Home::class,
    '/index' => Home::class,
    '/[id]' => fn() => require_once __DIR__ . '/ArtId/ns.php'
];