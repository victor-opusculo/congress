<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Spectators\SpectatorSubscription;

require_once "../../../vendor/autoload.php";

$messages = [];

if (isset($_SESSION['admin_passhash']))
{
    $conn = Connection::create();
    $getter = new SpectatorSubscription();
    $getter->setCryptKey(Connection::getCryptoKey());
    $fullData = $getter->getMultiple($conn, $_GET["q"] ?? "", $_GET["orderBy"] ?? "", null, null);
    $fileName = "Congresso-espectadores_" . date("d-m-Y_H-i-s") . ".csv";
    if (!$fullData) die("Não há dados de acordo com o critério atual de pesquisa.");

    $processedData = Data::transformDataRows($fullData, 
    [
        'ID' => fn($s) => $s->id,
        'Nome' => fn($s) => $s->name,
        'E-mail' => fn($s) => $s->email,
        'Telefone' => fn($s) => $s->data_json->telephone,
        'Cidade' => fn($s) => $s->data_json->city,
        'Estado' => fn($s) => $s->data_json->stateUf,
        'Área de interesse' => fn($s) => $s->data_json->areaOfInterest ?? '',
        'Deficiência' => fn($s) => $s->data_json->disabilityInfo ?? '',
        'Data de inscrição' => fn($s) => date_create($s->subscription_datetime)->format('d/m/Y H:i:s')
    ]);

    header('Content-Encoding: UTF-8');
    header("Content-type: text/csv; charset=UTF-8");
    header("Content-Disposition: attachment; filename=$fileName");

    $output = fopen("php://output", "w");
    $header = array_keys($processedData[0]);

    fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);
    //fwrite($output, "sep=," . PHP_EOL);

    fputcsv($output, $header, ";");

    foreach($processedData as $row)
    {
        fputcsv($output, $row, ";");
    }

    fclose($output);
}
else
    die("Administrador não logado!");