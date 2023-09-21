<?php
namespace Congress\Components\Data;

use PComp\Component;
use PComp\View;

class DataGrid extends Component
{
    public function setUp()
    {

    }

    protected ?array $dataRows;
    protected ?string $detailsButtonURL = null, $editButtonURL = null, $deleteButtonURL = null;
    protected ?string $selectButtonOnClick = null;
    protected ?string $columnNameAsDetailsButton = null;
    protected ?string $rudButtonsFunctionParamName = 'id';
    protected array $columnsToHide = [];
    protected array $customButtons = [];

	public array $customButtonsParameters = [];

    protected static function formatCellContent($value) : Component
    {
        if ($value instanceof DataGridCellValue)
            return View::rawText($value->generateHtml());
        else
            return View::rawText(nl2br(\Congress\Lib\Helpers\Data::hsc($value)));
    }

    protected static function applyCustomButtonsParameters(string $buttonUrl, array $parametersNamesArray, array $currentDataRow) : string
    {
        $finalUrl = $buttonUrl;
        foreach ($parametersNamesArray as $name => $columnNameOrFixed)
            if ($columnNameOrFixed instanceof FixedParameter)
                $finalUrl = str_replace('{' . $name . '}', (string)$columnNameOrFixed, $finalUrl);
            else if (isset($currentDataRow[$columnNameOrFixed]))
                $finalUrl = str_replace('{' . $name . '}', $currentDataRow[$columnNameOrFixed], $finalUrl);
            else
                $finalUrl = "#";
        return $finalUrl;
    }

    protected function markup(): Component|array|null
    {
        $colCount = 0;
        $colCount2 = 0;

        if (!isset($this->dataRows[0]))
            return View::text('Não há dados disponíveis.');

        return View::tag('table', class: 'responsibleTable', children: 
        [
            View::tag('thead', children:
            [
                View::tag('tr', children: 
                [
                    ...array_map( function($col) use (&$colCount)
                    {
                        $colCount++;
                        $comps = [];
                        if (in_array($col, $this->columnsToHide) === false)
                            $comps[] = View::tag('th', children: [ View::text($col) ]);

                        if ($colCount === count($this->dataRows[0]))
                        {
                            if (isset($this->detailsButtonURL) && !isset($this->columnNameAsDetailsButton)) $comps[] = View::tag('th', class: 'w-5', children: [ View::text('Detalhes') ]);
                            if (isset($this->editButtonURL)) $comps[] = View::tag('th', class: 'w-5', children: [ View::text('Editar') ]);
                            if (isset($this->deleteButtonURL)) $comps[] = View::tag('th', class: 'w-5', children: [ View::text('Excluir') ]);
                            if (isset($this->selectButtonOnClick)) $comps[] = View::tag('th', class: 'w-5', children: [ View::text('Selecionar') ]);

                            if (count($this->customButtons) > 0)
                                foreach ($this->customButtons as $label => $link)
                                    $comps[] = View::tag('th', class: 'w-5', children: [ View::text($label) ]);
                        }

                        return $comps;

                    }, array_keys($this->dataRows[0]))
                ])
            ]),
            View::tag('tbody', children: 
            [
                ...array_map(function($row) use (&$colCount2)
                {
                    return View::tag('tr', children:
                    [
                        ...array_map( function($column, $value) use (&$colCount2, $row)
                        {
                            $colCount2++;
                            $comps = [];
                            if (in_array($column, $this->columnsToHide) === false)
                            {
                                if (isset($this->columnNameAsDetailsButton) && $this->columnNameAsDetailsButton === $column)
                                    $comps[] = View::tag('td', ...['data-th' => $column], children:
                                    [
                                        View::tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->detailsButtonURL), children:
                                        [
                                            self::formatCellContent($value)
                                        ])
                                    ]);
                                else
                                    $comps[] = View::tag('td', ...['data-th' => $column], children: [ self::formatCellContent($value) ]);
                            }

                            if ($colCount2 === count($row))
                            {
                                if (isset($this->detailsButtonURL) && !isset($this->columnNameAsDetailsButton)) $comps[] = View::tag('td', ...[ 'data-th' => 'Detalhes' ], class: 'w-5', children: 
                                [ 
                                    View::tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->detailsButtonURL), children: [ View::text('Detalhes') ])
                                ]);
                                if (isset($this->editButtonURL)) $comps[] = View::tag('td', ...[ 'data-th' => 'Editar' ], class: 'w-5', children: 
                                [ 
                                    View::tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->editButtonURL), children: [ View::text('Editar') ])
                                ]);
                                if (isset($this->deleteButtonURL)) $comps[] = View::tag('td', ...[ 'data-th' => 'Excluir' ], class: 'w-5', children: 
                                [ 
                                    View::tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->deleteButtonURL), children: [ View::text('Excluir') ])
                                ]);
                                if (isset($this->selectButtonOnClick)) $comps[] = View::tag('td', ...[ 'data-th' => 'Selecionar' ], class: 'w-5', children: 
                                [
                                    View::tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->selectButtonOnClick), children: [ View::text('Selecionar') ])
                                ]);

                                if (count($this->customButtons) > 0)
                                    foreach ($this->customButtons as $label => $link)
                                        $comps[] = View::tag('td', ...[ 'data-th' => $label ], class: 'w-5', children: 
                                        [
                                            View::tag('a', class: 'link text-lg', href: self::applyCustomButtonsParameters($link, $this->customButtonsParameters, $row), children: [ View::text($label) ])
                                        ]);
                            }

                            return $comps;
                        }, array_keys($row), $row)
                    ]);
                }, $this->dataRows)
            ])
        ]);
    }
}