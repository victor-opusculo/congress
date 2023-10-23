<?php
namespace Congress\App\Infos;

use Congress\Lib\Helpers\System;
use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component, HeadManager};

class SubmissionRules extends Component
{
    protected function setUp()
    {
		HeadManager::$title = "Normas de Submissão";
    } 

    protected function markup() : Component|array
    {
        return
        [
            View::tag('div', class: 'mx-auto max-w-[800px] min-w-[300px] bg-slate-200 border border-slate-700 text-base p-4 my-4', children:
            [
                View::tag('h2', children: [ View::text('Normas para submissão de trabalhos ao ' . System::eventName()) ]),
                View::tag('p', class: 'my-2', children:
                [
                    View::text('Os autores devem verificar a conformidade da submissão em relação aos itens listados a seguir.')
                ]),
                View::tag('section', children:
                [
                    View::tag('p', class: 'font-bold', children: [ View::text('Aspectos gerais:') ]),
                    View::tag('ul', class: 'list-disc ml-4', children: 
                    [
                        View::tag('li', children: [ View::text('O limite de autor e co-autores deve ser de 5 (cinco) por trabalho;') ]),
                        View::tag('li', children: [ View::text('O manuscrito deve estar em formato Microsoft Word;') ]),
                        View::tag('li', children: [ View::text('As normais gerais de submissão devem obedecer aos padrões ABNT, com normas de citações conforme ABNT NBR 10520 e referências de acordo com ABNT NBR 6023;') ]),
                        View::tag('li', children: [ View::text('O manuscrito deverá ser submetido sem identificação dos autoros (conforme modelo do template). Atentar para que nenhuma forma de identificação automática deve ser percebida, sob o risco de eliminação da submissão. Caso seja aprovado, será solicitado o reenvio com a identificação;') ]),
                        View::tag('li', children: [ View::text('Somente serão aceitos os manuscritos enviados pelo '), View::tag('a', href: URLGenerator::generatePageUrl('/submitter'), class: 'link', children: [ View::text('sistema') ] ), View::text(';') ]),
                        View::tag('li', children: [ View::text('A estrutura do artigo deve conter: introdução, referencial teórico, aspectos metodológicos,  resultados e discussões, conclusão/considerações finais e referências. Subdivisões de seções ficam a critério dos autores.') ]),
                    ])
                ]),
                View::tag('section', class: 'mt-4', children:
                [
                    View::tag('p', class: 'font-bold', children: [ View::text('A formatação dos artigos deve obedecer aos critérios:') ]),
                    View::tag('ul', class: 'list-disc ml-4', children: 
                    [
                        View::tag('li', children: [ View::text('Os artigos usarão a fonte Times New Roman em todos os seus elementos;') ]),
                        View::tag('li', children: [ View::text('O Título do artigo deve vir: centralizado; caixa alta; negrito; tamanho 14; espaçamento 1,5; seguido de subtítulo em caixa baixa;') ]),
                        View::tag('li', children: [ View::text('Palavras-chave: entre três e cinco palavras, separadas por ponto e vírgula;') ]),
                        View::tag('li', children: [ View::text('O Resumo pode ter no máximo 1.000 caracteres com espaço, devendo conter o problema de pesquisa, objetivo, abordagem metodológica e breves resultados. Apresentado com: Parágrafo único; sem recuo; justificado e espaço simples;') ]),
                        View::tag('li', children: [ View::text('O texto submetido deve conter entre 15 e 20 páginas, com fonte Times New Roman, tamanho 12 (exceto para o título); espaçamento entre linhas 1,5.') ])
                    ])
                ]),

                View::tag('section', class: 'mt-4', children:
                [
                    View::tag('p', class: 'font-bold', children: [ View::text('Arquivo modelo para escrita dos artigos:') ]),
                    View::tag('ul', class: 'list-disc ml-4', children: 
                    [
                        View::tag('li', children: [ View::tag('a', href: URLGenerator::generateFileUrl('assets/docs/template-sem-identificacao.docx'), class: 'link', children: [ View::text('Template de Submissão Sem identificação dos autores') ]) ]),
                        View::tag('li', children: [ View::tag('a', href: URLGenerator::generateFileUrl('assets/docs/template-com-identificacao.docx'), class: 'link', children: [ View::text('Template de Submissão Com identificação dos autores') ]), View::text('(utilizar somente se o artigo for aprovado)') ])
                    ])
                ]),

                View::tag('section', class: 'mt-4', children:
                [
                    View::text('Obs.: Apenas os trabalhos apresentados serão publicados nos Anais do evento.')
                ])
            ])
        ];
    }
}