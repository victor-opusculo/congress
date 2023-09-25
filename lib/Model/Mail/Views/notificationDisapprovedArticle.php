<?php 
require_once __DIR__ . '/../../../../vendor/autoload.php'; 
use Congress\Lib\Helpers\URLGenerator; 
?>

<h2 style="font-size: 1.3em;">Olá, <?php echo $submitterName; ?>!</h2>
<p>Informamos que seu artigo "<?php echo $articleTitle; ?>" foi <span style="color:red; font-weight:bold;">reprovado</span> por um de nossos avaliadores, com o seguinte feedback:
</p>
<div style="margin: 5px; padding: 5px; font-size: 16px; background-color: lightgray;">
    <?php echo $articleFeedback; ?>
</div>
<p>
Se os erros forem sanáveis, você pode corrigir o artigo e reenviá-lo, cadastrando-o como um novo em <a href="<?= 'http://' . $_SERVER["HTTP_HOST"] . URLGenerator::generatePageUrl('submitter/panel') ?>">nosso site</a>.
</p>