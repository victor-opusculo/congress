<?php
session_name("congress_assessor_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;

require_once "../../vendor/autoload.php";

$messages = ['Você saiu!'];
unset($_SESSION['assessor_id']);
session_unset();
session_destroy();

header('location:' . URLGenerator::generatePageUrl('/assessor/login', [ 'messages' => implode('//', $messages) ]));

