<?php

use Phinx\Migration\AbstractMigration;

class AurynWorkshopInitial extends AbstractMigration
{
    public function change()
    {
        // ************************************************
        // ************************************************
        $table = $this->table('user');
        $table
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();

        $table = $this->table('user_password_login');
        $table
            ->addColumn('username', 'string')
            ->addColumn('password_hash', 'string', ['length' => 255])
            ->addColumn('user_id', 'integer')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex(array('username'), array('unique' => true))
            ->addForeignKey('user_id', 'user', 'id')
            ->create();



        $table = $this->table('example_data');
        $table
            ->addColumn('foo', 'string')
            ->addColumn('bar', 'string')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}