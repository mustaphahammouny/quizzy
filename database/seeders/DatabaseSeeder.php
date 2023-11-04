<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    private $excludeTables = ['migrations'];

    public function run(): void
    {
        $this->clear();

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
    }

    private function clear(): void
    {
        $tables = DB::select('SHOW TABLES');

        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            $tableName = reset($table);

            if (!in_array($tableName, $this->excludeTables)) {
                DB::table($tableName)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
