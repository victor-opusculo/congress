<?php
namespace Congress\App\Assessor\Panel\AvailableArticles\ArtId;

use Congress\Components\{Label, Link, Site\PageTitle};
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Articles\ArticleStatus;
use Congress\Lib\Model\Database\Connection;
use PComp\{Component, HeadManager, View};

class ViewArticle extends Component
{
    public function setUp()
    {
        HeadManager::$title = 'Painel do avaliador: Ver artigo';
        $conn = Connection::get();
        $this->article = (new Article([ 'id' => $this->id ]))->getSingle($conn);
    }

    protected int $id;
    protected Article $article;

    protected function markup(): Component|array|null
    {
        return 
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Ver artigo disponível'),
            View::component(Label::class, label: 'ID', labelBold: true, children: [ View::text($this->article->id) ]),
            View::component(Label::class, label: 'Título', labelBold: true, children: [ View::text($this->article->title) ]),
            View::component(Label::class, label: 'Palavras-chave', labelBold: true, children: [ View::text($this->article->keywords) ]),
            View::component(Label::class, label: 'Resumo', labelBold: true, lineBreak: true, children: [ View::rawText(nl2br(Data::hsc($this->article->resume))) ]),
            View::component(Label::class, label: 'Status', labelBold: true, children: [ View::text($this->article->translateStatus()) ]),
            View::component(Label::class, label: 'Arquivo (sem identificação)', labelBold: true, children: 
            [
                View::component(Link::class, class: 'link', url: URLGenerator::generateScriptUrl('assessors/available_articles/view_not_idded.php', [ 'id' => $this->article->id ]), children: [ View::text("Visualizar") ])
            ]),

            View::tag('div', class: 'text-center mt-2', children:
            [
                $this->article->status === ArticleStatus::WaitingAssessor->value ?
                    View::component(Link::class, class:'btn', url: URLGenerator::generateScriptUrl('assessors/available_articles/accept.php', [ 'id' => $this->article->id ]), children: [ View::text('Aceito avaliar este artigo') ])
                :
                    View::text('Artigo já aceito por avaliador!')
            ])
        ];
    }
}