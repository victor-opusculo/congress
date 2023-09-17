<?php
namespace Congress\Components\Data;

interface DataGridCellValue
{
    public function generateHtml() : string;
}