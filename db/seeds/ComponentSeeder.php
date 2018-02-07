<?php


use Phinx\Seed\AbstractSeed;

class ComponentSeeder extends AbstractSeed
{
    public function run()
    {
        $this->basicAccount();
        $this->someData();
    }

    private function basicAccount()
    {
        $data = [];
        $components = $this->table('user');
        $result = $components->insert($data);

        $lastInsert = $this->getAdapter()->getConnection()->lastInsertId();

        $data = [
            [
                'username' => 'admin',
                'password_hash' => generate_password_hash('password'),
                'user_id' => ,
            ],
        ];

        $components = $this->table('user_password_login');
        $components->insert($data)
            ->save();
    }

    private function someData()
    {
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'foo' => 'foo' . $i,
                'bar' => 'bar' . $i
            ];
        }

        $components = $this->table('example_data');
        $components->insert($data)
            ->save();
    }
}