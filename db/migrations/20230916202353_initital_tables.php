<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InititalTables extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $table = $this->table('submitters');
        $table->addColumn('email', 'string', [ 'limit' => 140, 'null' => false ])
        ->addColumn('password_hash', 'string', [ 'limit' => 280, 'null' => false ])
        ->addColumn('name', 'string', [ 'limit' => 140, 'null' => false ])
        ->addIndex('email', [ 'unique' => true ])
        ->addIndex(['name', 'email'], [ 'type' => 'fulltext' ])
        ->create();

        $table->insert(
        [ 
            'email' => 'victor.ventura@uol.com.br',
            'password_hash' => password_hash('12345678', PASSWORD_DEFAULT),
            'name' => 'Victor Opusculo Oliveira Ventura de Almeida'
        ])
        ->saveData();

        $table = $this->table('assessors');
        $table
        ->addColumn('email', 'string', [ 'limit' => 140, 'null' => false ])
        ->addColumn('password_hash', 'string', [ 'limit' => 280, 'null' => false ])
        ->addColumn('name', 'string', [ 'limit' => 140, 'null' => false ])
        ->addIndex('email', [ 'unique' => true ])
        ->addIndex(['name', 'email'], [ 'type' => 'fulltext' ])
        ->create();

        $table->insert(
        [ 
            'email' => 'victor.ventura@uol.com.br',
            'password_hash' => password_hash('12345678', PASSWORD_DEFAULT),
            'name' => 'Victor Opusculo Oliveira Ventura de Almeida'
        ])
        ->saveData();

        $table = $this->table('articles');
        $table
        ->addColumn('submitter_id', 'integer', [ 'signed' => false ])
        ->addColumn('title', 'string', [ 'limit' => 400, 'null' => false ])
        ->addColumn('resume', 'text')
        ->addColumn('authors', 'json', [ 'null' => false ])
        ->addColumn('keywords', 'string', [ 'limit' => 280, 'null' => false ])
        ->addColumn('submitted_at', 'datetime', [ 'null' => false ])
        ->addColumn('no_idded_filename', 'string', [ 'limit' => 255, 'null' => false ])
        ->addColumn('idded_filename', 'string', [ 'null' => true ])
        ->addColumn('status', 'string', [ 'limit' => 100, 'null' => false ])
        ->addColumn('is_approved', 'boolean', [ 'null' => false ])
        ->addColumn('evaluator_assessor_id', 'integer', [ 'signed' => false, 'null' => true ])
        ->addColumn('evaluator_feedback', 'text', [ 'null' => true ])
        ->addForeignKey('submitter_id', 'submitters', ['id'], [ 'delete' => 'SET_NULL', 'update' => 'CASCADE' ])
        ->addForeignKey('evaluator_assessor_id', 'assessors', 'id', [ 'delete' => 'SET_NULL', 'update' => 'CASCADE' ])
        ->addIndex(['title', 'resume', 'keywords'], [ 'type' => 'fulltext' ])
        ->create();

        $table = $this->table('settings', [ 'id' => false, 'primary_key' => ['name'] ]);
        $table
        ->addColumn('name', 'char', [ 'limit' => 100, 'null' => false ])
        ->addColumn('value', 'text')
        ->create();

        $table->insert([ ['name' => 'ADMIN_PASSWORD_HASH', 'value' => password_hash('12345678', PASSWORD_DEFAULT)] ])->saveData();
    }

    public function down(): void
    {
        $this->table('settings')->drop()->save();
        $this->table('articles')->drop()->save();
        $this->table('assessors')->drop()->save();
        $this->table('submitters')->drop()->save();
    }
}
