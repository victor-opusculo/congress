<?php
session_name("congress_admin_user");
session_start();

use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Settings\AdminPassword;
use Congress\Lib\Model\Database\Connection;
use Congress\Lib\Model\Settings\SpectatorSubscriptionsClosureDate;
use Congress\Lib\Model\Settings\SubmissionsClosureDate;

require_once "../../vendor/autoload.php";

$messages = [];
if (isset($_SESSION['admin_passhash']))
{
    $conn = Connection::create();
    try
    {
        $adminPass = (new AdminPassword())->getSingle($conn);

        if ($_POST['txtOldPassword'])
        {
            if ($adminPass->verifyPassword($_POST['txtOldPassword']))
            {
                $adminPass->changePassword($_POST['txtNewPassword']);
            }
            else
                throw new Exception('Senha atual incorreta!');
        }

        $result = $adminPass->save($conn);
        if ($result['affectedRows'] > 0)
            $messages[] = "Senha salva com sucesso!";
        else
            $messages[] = "Senha não alterada!";

        $submissionClosure = new SubmissionsClosureDate();
        $submissionClosure->fillPropertiesFromFormInput($_POST);
        $result = $submissionClosure->save($conn);

        if ($result['affectedRows'] > 0)
            $messages[] = "Data limite de submissão de artigos alterada!";

        $spectatorsClosure = new SpectatorSubscriptionsClosureDate();
        $spectatorsClosure->fillPropertiesFromFormInput($_POST);
        $result = $spectatorsClosure->save($conn);

        if ($result['affectedRows'] > 0)
            $messages[] = "Data limite para inscrição de espectadores alterada!";
    }
    catch (\Exception $e)
    {
        $messages[] = $e->getMessage();
    }
}

header('location:' . URLGenerator::generatePageUrl('/admin/panel/settings', [ 'messages' => implode('//', $messages) ]));

