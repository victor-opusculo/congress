<?php 
require_once __DIR__ . '/../../../../vendor/autoload.php'; 
use Congress\Lib\Helpers\URLGenerator; 
?>

<h2 style="font-size: 1.3em;">Olá, <?php echo $submitterName; ?>!</h2>
<p>Informamos que seu artigo "<?php echo $articleTitle; ?>" foi aprovado por um de nossos avaliadores.
Pedimos que você acesse <a href="<?= 'http://' . $_SERVER["HTTP_HOST"] . URLGenerator::generatePageUrl('submitter/panel') ?>">nosso site</a> e faça upload da versão identificada (com autores), por meio da página de detalhes do artigo aprovado.</p>