<?php
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Spectators\SpectatorSubscription;

require_once "../../vendor/autoload.php";

$messages = [];

$conn = Connection::create();
try
{
    unset($_POST['specSubscriptions:id']);
    $subscription = new SpectatorSubscription();
    $subscription->setCryptKey(Connection::getCryptoKey());
    $subscription->fillPropertiesFromFormInput($_POST);

    if ($subscription->existsByEmail($conn))
        $messages[] = "Sua inscrição foi feita e está salva em nosso cadastro!";
    else
        $messages[] = "Inscrição não localizada para o e-mail informado!";
}
catch (\Exception $e)
{
    $messages[] = $e->getMessage();
}

header('location:' . URLGenerator::generatePageUrl('/spectator/verify', [ 'messages' => implode('//', $messages) ]), true, 303);

