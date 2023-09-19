<?php
session_name("congress_submitter_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;

require_once "../../vendor/autoload.php";

$messages = ['Você saiu!'];
unset($_SESSION['submitter_id']);
session_unset();
session_destroy();

header('location:' . URLGenerator::generatePageUrl('/submitter/login', [ 'messages' => implode('//', $messages) ]));

