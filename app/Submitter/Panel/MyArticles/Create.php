<?php
namespace Congress\App\Submitter\Panel\MyArticles;

use Congress\Components\Label;
use Congress\Components\Site\PageTitle;
use Congress\Lib\Helpers\URLGenerator;
use Congress\Lib\Model\Articles\Upload\NotIddedArticleUpload;
use PComp\{View, Component, Context, HeadManager, ScriptManager};

class Create extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Painel do Autor: Cadastrar artigo";
        ScriptManager::registerScript('submitterCreateArticleScript', file_get_contents(__DIR__ . '/create.js'));
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::component(PageTitle::class, tag: 'h2', text: 'Cadastrar Artigo'),
            View::tag('form', id: 'frmCreateArticle', method: 'post', action: URLGenerator::generateScriptUrl('submitters/my_articles/create.php'), enctype: 'multipart/form-data', children:
            [
                View::component(Label::class, label: 'Título', children: [ View::scTag('input', type: 'text', required: 'req', maxlength: 380, class: 'w-full', name:'articles:txtTitle') ]),
                View::component(Label::class, label: 'Resumo', lineBreak: true, children: 
                [ 
                    View::tag('textarea', maxlength: 65000, rows: 5, class: 'w-full', name:'articles:txtResume') 
                ]),
                View::component(Label::class, label: 'Palavras-chave', children: [ View::scTag('input', type: 'text', required: 'req', maxlength: 380, class: 'w-full', name:'articles:txtKeywords') ]),

                View::component(Label::class, label: 'Autores', lineBreak: true, children:
                [
                    View::tag('ol', id: 'olAuthors', class: 'list-decimal ml-4', children:
                    [
                    ])
                ]),
                View::tag('button', id: 'btnAddAuthor', type: 'button', class: 'btn ml-2', children: [ View::text('Adicionar') ]),

                View::component(Label::class, label: 'Arquivo', lineBreak: true, children:
                [
                    View::tag('div', class: 'flex flex-col', children: 
                    [
                        View::scTag('input', id: 'fileArticleNotIdded', name: 'fileArticleNotIdded', type: 'file', class: 'file:btn', required: 'req', accept: implode(',', NotIddedArticleUpload::ALLOWED_TYPES)),
                        View::tag('span', class: 'text-red-500 font-bold', children: [ View::text('ATENÇÃO: Você deve anexar a versão do artigo sem identificação dos autores. Caso ele seja aprovado pelo avaliador, solicitaremos depois que você envie a versão com identificação.') ]),
                        View::tag('span', children: [ View::text('(Tamanho máximo de 10MB. Formatos suportados: DOC e DOCX)') ])
                    ])
                ]),

                View::tag('div', class: 'text-center', children: 
                [
                    View::scTag('input', id: 'hidSubmitterName', type: 'hidden', value: Context::get('submitter_name')),
                    View::scTag('input', id: 'hidAuthorsJson', type: 'hidden', name: 'articles:hidAuthors', value: ''),
                    View::tag('button', type: 'submit', class: 'btn', children: [ View::text('Enviar!') ])
                ])
            ])
        ];
    }
}