<?php
namespace Congress\App\Admin\Panel\ManageArticles\ArtId;

use Congress\Components\Label;
use Congress\Components\Panels\DeleteEntityForm;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\Data;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Article;
use Congress\Lib\Model\Assessors\Assessor;
use Congress\Lib\Model\Database\Connection;
use PComp\{View, Component, Context, HeadManager};

class DeleteArticle extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Painel do Administrador: Excluir artigo";

        $conn = Connection::get();

        try
        {
            $artGetter = new Article([ 'id' => $this->id ]);
            $this->article = $artGetter->getSingle($conn);
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    } 

    protected int $id;
    protected Article $article;

    protected function markup() : Component|array|null
    {
        return isset($this->article) ?
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Excluir artigo'),

            View::component(DeleteEntityForm::class, 
                            deleteScriptUrl: URLGenerator::generateScriptUrl('/admin/man_articles/delete.php', [ 'id' => $this->article->id ]),
                            children:
                            [
                                View::tag('div', class: 'text-center font-bold mb-2', children: [ View::text('Você realmente deseja excluir este artigo? Esta ação é irreversível!') ]),
                                View::component(Label::class, label: 'ID', labelBold: true, children: [ View::text($this->article->id) ] ),
                                View::component(Label::class, label: 'Título', labelBold: true, children: [ View::text($this->article->title) ] ),
                                View::component(Label::class, label: 'Palavras-chave', labelBold: true, children: [ View::text($this->article->keywords) ] )
                            ])
        ]: 
        null;
    }
}