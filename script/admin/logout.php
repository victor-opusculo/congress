<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;

require_once "../../vendor/autoload.php";

$messages = ['Você saiu!'];
unset($_SESSION['admin_passhash']);
session_unset();
session_destroy();

header('location:' . URLGenerator::generatePageUrl('/admin/login', [ 'messages' => implode('//', $messages) ]));

