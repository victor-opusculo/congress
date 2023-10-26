<?php
namespace Congress\App\Spectator;

require_once "Register.php";
require_once "Verify.php";

return 
[
    '/' => Register::class,
    '/register' => Register::class,
    '/verify' => Verify::class
];