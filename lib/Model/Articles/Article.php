<?php
namespace Congress\Lib\Model\Articles;

use Congress\Lib\Model\DataEntity;
use Congress\Lib\Model\DataProperty;
use Congress\Lib\Model\Exceptions\DatabaseEntityNotFound;
use Congress\Lib\Model\FileUploadUtils;
use Congress\Lib\Model\SqlSelector;
use DateTime;
use mysqli;

class Article extends DataEntity
{
    public function __construct($initialValues = null)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'submitter_id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'title' => new DataProperty('txtTitle', 'Sem título', DataProperty::MYSQL_STRING),
            'resume' => new DataProperty('txtResume', '', DataProperty::MYSQL_STRING),
            'authors' => new DataProperty('hidAuthors', '[]', DataProperty::MYSQL_STRING),
            'keywords' => new DataProperty('txtKeywords', '', DataProperty::MYSQL_STRING),
            'submitted_at' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'no_idded_filename' => new DataProperty('', 'not_idded', DataProperty::MYSQL_STRING),
            'idded_filename' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'status' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'is_approved' => new DataProperty('', 0, DataProperty::MYSQL_INT),
            'evaluator_assessor_id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'evaluator_feedback' => new DataProperty('txtFeedbackMessage', null, DataProperty::MYSQL_STRING)
        ];

        $this->initializeWithValues($initialValues);
    }

    protected string $databaseTable = 'articles';
    protected string $formFieldPrefixName = 'articles';
    protected array $primaryKeys = ['id'];
    protected string $fileInputNameNotIdded = 'fileArticleNotIdded';

    protected function newInstanceFromDataRow($dataRow)
    {
        return new self($dataRow);
    }

    public function translateStatus() : string
    {
        return ArticleStatus::translate($this->properties->status->getValue());
    }

    public function getAuthors() : array
    {
        $json = $this->properties->authors->getValue();
        $decoded = json_decode($json);
        return $decoded;
    }

    public function getCount(mysqli $conn, string $searchKeywords, string $status, ?int $submitter_id, ?int $assessor_id) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("{$this->databaseTable}.*")
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector
            ->addWhereClause('MATCH (title, resume, keywords) AGAINST (?)')
            ->addValue('s', $searchKeywords);
        }

        if ($status)
        {
            if ($selector->hasWhereClauses())
                $selector->addWhereClause('AND status = ?')->addValue('s', $status);
            else
                $selector->addWhereClause('status = ?')->addValue('s', $status);
        }

        if ($submitter_id)
            $selector
            ->addWhereClause($selector->hasWhereClauses() ? 'AND submitter_id = ?' : 'submitter_id = ?')
            ->addValue('i', $this->properties->submitter_id->getValue());

        if ($assessor_id)
            $selector
            ->addWhereClause($selector->hasWhereClauses() ? 'AND evaluator_assessor_id = ?' : 'evaluator_assessor_id = ?')
            ->addValue('i', $this->properties->evaluator_assessor_id->getValue());

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return (int)$count > 0;
    }

    public function getMultiple(mysqli $conn, string $searchKeywords, string $status, string $orderBy, int $page, int $numResultsOnPage, ?int $submitter_id = null, ?int $assessor_id = null) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("{$this->databaseTable}.*")
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector
            ->addWhereClause('MATCH (title, resume, keywords) AGAINST (?)')
            ->addValue('s', $searchKeywords);
        }

        if ($status)
        {
            if ($selector->hasWhereClauses())
                $selector->addWhereClause('AND status = ?')->addValue('s', $status);
            else
                $selector->addWhereClause('status = ?')->addValue('s', $status);
        }

        if ($submitter_id)
            $selector
            ->addWhereClause($selector->hasWhereClauses() ? 'AND submitter_id = ?' : 'submitter_id = ?')
            ->addValue('i', $this->properties->submitter_id->getValue());

        if ($assessor_id)
            $selector
            ->addWhereClause($selector->hasWhereClauses() ? 'AND evaluator_assessor_id = ?' : 'evaluator_assessor_id = ?')
            ->addValue('i', $this->properties->evaluator_assessor_id->getValue());

        $selector->setOrderBy(match ($orderBy)
        {
            'title' => 'title asc',
            'status' => 'status asc',
            'submitted_at' => 'submitted_at desc',
            'id' => 'id desc',
            default => 'id desc'
        });

        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector
        ->setLimit('?,?')
        ->addValues('ii', [ $calc_page, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([$this, 'newInstanceFromDataRow'], $drs);
    }

    public function getSingleFromSubmitter(mysqli $conn) : self
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("{$this->databaseTable}.*")
        ->setTable($this->databaseTable)
        ->addWhereClause($this->getWhereQueryColumnName('id') . ' = ?')
        ->addWhereClause('AND ' . $this->getWhereQueryColumnName('submitter_id') . ' = ?')
        ->addValues('ii', [ $this->id, $this->submitter_id ]);

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound('Artigo não encontrado!', $this->databaseTable);
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        Upload\NotIddedArticleUpload::checkForUploadError($this->postFiles, $this->fileInputNameNotIdded);
        $extension = Upload\NotIddedArticleUpload::getExtension($this->postFiles, $this->fileInputNameNotIdded);
        $this->properties->no_idded_filename->setValue("not_idded.$extension");
        $this->properties->status->setValue(ArticleStatus::WaitingAssessor->value);
        $this->properties->submitted_at->setValue((new DateTime())->format('Y-m-d H:i:s'));
        return 0;
    }

    public function afterDatabaseInsert(mysqli $conn, $insertResult)
    {
        Upload\NotIddedArticleUpload::uploadArticleFile($insertResult['newId'], $this->postFiles, $this->fileInputNameNotIdded);
        return $insertResult;
    }
}