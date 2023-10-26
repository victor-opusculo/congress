<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SubscriptionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('spectators_subscriptions');
        $table
        ->addColumn('name', 'binary', [ 'limit' => 300, 'null' => false ])
        ->addColumn('email', 'binary', [ 'limit' => 300, 'null' => false ])
        ->addColumn('data_json', 'binary', [ 'limit' => 4000, 'null' => false ])
        ->addColumn('subscription_datetime', 'datetime', [ 'null' => false ])
        ->addIndex('email', [ 'unique' => true, 'limit' => [ 'email' => 300 ] ])
        ->create();
    }
}
