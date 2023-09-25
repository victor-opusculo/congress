<?php
namespace Congress\Lib\Model\Mail;

use Congress\Lib\Helpers\System;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Submitters\Submitter;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../../../vendor/autoload.php';

final class NotificationDisapprovedArticle
{
    public static function send(Article $article, Submitter $submitter)
    {
        $configs = System::getMailConfigs();

        $mail = new PHPMailer();
        // DEFINI��O DOS DADOS DE AUTENTICA��O - Voc� deve alterar conforme o seu dom�nio!
        $mail->IsSMTP(); // Define que a mensagem ser� SMTP
        $mail->Host = $configs['host']; // Seu endere�o de host SMTP
        $mail->SMTPAuth = true; // Define que ser� utilizada a autentica��o -  Mantenha o valor "true"
        $mail->Port = $configs['port']; // Porta de comunica��o SMTP - Mantenha o valor "587"
        $mail->SMTPSecure = false; // Define se � utilizado SSL/TLS - Mantenha o valor "false"
        $mail->SMTPAutoTLS = true; // Define se, por padr�o, ser� utilizado TLS - Mantenha o valor "false"
        $mail->Username = $configs['username']; // Conta de email existente e ativa em seu dom�nio
        $mail->Password = $configs['password']; // Senha da sua conta de email
        // DADOS DO REMETENTE
        $mail->Sender = $configs['sender']; // Conta de email existente e ativa em seu dom�nio
        $mail->From = $configs['sender']; // Sua conta de email que ser� remetente da mensagem
        $mail->FromName = System::eventName(); // Nome da conta de email
        // DADOS DO DESTINAT�RIO
        $mail->AddAddress($submitter->email, $submitter->name); // Define qual conta de email receber� a mensagem

        // Defini��o de HTML/codifica��o
        $mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
        $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
        // DEFINI��O DA MENSAGEM
        $mail->Subject  = System::eventName() . " - Seu artigo foi reprovado!"; // Assunto da mensagem

        ob_start();
        $__VIEW = 'notificationDisapprovedArticle.php';
        $submitterName = $submitter->name;
        $articleTitle = $article->title;
        $articleFeedback = $article->evaluator_feedback;

        require_once (__DIR__ . '/baseMessageLayout.php');
        $emailBody = ob_get_clean();
        ob_end_clean();

        $mail->Body .= $emailBody;
        
        // ENVIO DO EMAIL
        $sent = $mail->Send();

        // Limpa os destinat�rios e os anexos
        $mail->ClearAllRecipients();

        // Exibe uma mensagem de resultado do envio (sucesso/erro)
        if ($sent) {
            return true;
        } else {
            throw new \Exception("Não foi possível enviar o e-mail! Detalhes do erro: " . $mail->ErrorInfo);
        }
    }
}