<?php
namespace Congress\App\Assessor\Panel\AcceptedArticles\ArtId;

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
        HeadManager::$title = 'Painel do avaliador: Ver artigo aceito';
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

            View::tag('form', 
                        method: 'post', 
                        action:  URLGenerator::generateScriptUrl('assessors/accepted_articles/approve_disapprove.php', [ 'id' => $this->article->id ]), 
                        children:
            [
                View::tag('fieldset', class: 'fieldset', children:
                [
                    View::tag('legend', children: [ View::text('Avaliação') ]),
                    View::component(Label::class, label: 'Mensagem de feedback', lineBreak: true, children: 
                    [
                        View::tag('textarea', required: 'req', name: 'articles:txtFeedbackMessage', rows: 5, class: 'w-full', children: [ View::text($this->article->evaluator_feedback) ]),
                    ]),
                    View::tag('label', class: 'mr-4', children: 
                    [
                        View::scTag('input', required: 'req', type: 'radio', name: 'radAction', value: 'approve'),
                        View::text(' Aprovar artigo')
                    ]),
                    View::tag('label', children: 
                    [
                        View::scTag('input', required: 'req', type: 'radio', name: 'radAction', value: 'disapprove'),
                        View::text(' Reprovar artigo')
                    ]),
                    View::tag('div', class: 'text-center mt-2', children:
                    [
                        View::tag('button', type: 'submit', class: 'btn', children: [ View::text('Enviar avaliação') ])
                    ])
                ])
            ]),

            empty($this->article->idded_filename) ?
                View::tag('div', class: 'text-right mt-10', children:
                [
                    View::component(Link::class, class: 'btn', url: URLGenerator::generateScriptUrl('assessors/accepted_articles/give_up.php', [ 'id' => $this->article->id ]), children: [ View::text('Desistir de avaliar este artigo') ])
                ])
            :
                null
        ];
    }
}