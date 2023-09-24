<?php
namespace Congress\App\Submitter\Panel\MyArticles\ArtId;

use Congress\Components\{Label, Link, Site\PageTitle};
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Database\Connection;
use PComp\{Component, HeadManager, View};

class ViewArticle extends Component
{
    public function setUp()
    {
        HeadManager::$title = 'Painel do autor: Ver artigo';
        $conn = Connection::get();
        $this->article = (new Article([ 'submitter_id' => $_SESSION['submitter_id'], 'id' => $this->id ]))->getSingleFromSubmitter($conn);
    }

    protected int $id;
    protected Article $article;

    protected function markup(): Component|array|null
    {
        return 
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Ver artigo'),
            View::component(Label::class, label: 'ID', labelBold: true, children: [ View::text($this->article->id) ]),
            View::component(Label::class, label: 'Título', labelBold: true, children: [ View::text($this->article->title) ]),
            View::component(Label::class, label: 'Palavras-chave', labelBold: true, children: [ View::text($this->article->keywords) ]),
            View::component(Label::class, label: 'Resumo', labelBold: true, lineBreak: true, children: [ View::rawText(nl2br(Data::hsc($this->article->resume))) ]),
            View::component(Label::class, label: 'Autores', labelBold: true, children: [ View::text(implode(', ', $this->article->getAuthors())) ]),
            View::component(Label::class, label: 'Status', labelBold: true, children: [ View::text($this->article->translateStatus()) ]),
            $this->article->evaluator_feedback ?
                View::component(Label::class, label: 'Feedback do avaliador', lineBreak: true, labelBold: true, children:
                [
                    View::rawText(nl2br(Data::hsc($this->article->evaluator_feedback)))
                ])
            :
                null,
            View::component(Label::class, label: 'Arquivo (sem identificação)', labelBold: true, children: 
            [
                View::component(Link::class, class: 'link', url: URLGenerator::generateScriptUrl('submitters/my_articles/view_not_idded.php', [ 'id' => $this->article->id ]), children: [ View::text("Visualizar") ])
            ]),
            View::component(Label::class, label: 'Arquivo (com identificação)', labelBold: true, children: 
            [
                (bool)$this->article->is_approved && $this->article->idded_filename ?
                    View::component(Link::class, class: 'link', url: URLGenerator::generateScriptUrl('submitters/my_articles/view_idded.php', [ 'id' => $this->article->id ]), children: [ View::text("Visualizar") ])
                :
                    ((bool)$this->article->is_approved && !$this->article->idded_filename ?
                        View::text('Artigo aprovado! Envie a versão identificada (com autores).')
                    :
                        View::text('***')
                    )
            ]),
        ];
    }
}