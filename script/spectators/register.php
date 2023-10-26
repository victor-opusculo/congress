<?php
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Settings\SpectatorSubscriptionsClosureDate;
use Congress\Lib\Model\Spectators\SpectatorSubscription;

require_once "../../vendor/autoload.php";

$messages = [];

$conn = Connection::create();
try
{

    $closure = date_create((new SpectatorSubscriptionsClosureDate)->getSingle($conn)->value ?? 'now');
    $today = date_create('now');

    if ($today >= $closure)
        throw new Exception('Inscrições encerradas!');

    unset($_POST['specSubscriptions:id']);
    $subscription = new SpectatorSubscription();
    $subscription->setCryptKey(Connection::getCryptoKey());
    $subscription->fillPropertiesFromFormInput($_POST);

    if ($subscription->existsByEmail($conn))
        throw new Exception('Você já se inscreveu!');

    $result = $subscription->save($conn);
    if ($result['newId'])
        $messages[] = "Inscrição feita com sucesso!";
    else
        throw new Exception("Erro: Inscrição não salva!");
}
catch (\Exception $e)
{
    $messages[] = $e->getMessage();
}

header('location:' . URLGenerator::generatePageUrl('/spectator/register', [ 'messages' => implode('//', $messages) ]), true, 303);

