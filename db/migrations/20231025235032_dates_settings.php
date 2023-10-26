<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DatesSettings extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('settings');
        $table
        ->insert(
        [
            [ 'name' => 'SUBMISSIONS_CLOSURE_DATE', 'value' => '2023-10-30' ],
            [ 'name' => 'SPECTATOR_SUBSCRIPTIONS_CLOSURE_DATE', 'value' => '2023-10-30' ]
        ])
        ->saveData();
    }

    public function down() : void
    {
        $qb = $this->getQueryBuilder();
        $qb->delete('settings')->whereInList('name', [ 'SUBMISSIONS_CLOSURE_DATE', 'SPECTATOR_SUBSCRIPTIONS_CLOSURE_DATE' ])->execute();
    }
}
