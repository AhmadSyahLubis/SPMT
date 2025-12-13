<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckTableStructure extends Command
{
    protected $signature = 'table:structure {table}';
    protected $description = 'Check the structure of a database table';

    public function handle()
    {
        $table = $this->argument('table');

        if (!Schema::hasTable($table)) {
            $this->error("Table {$table} does not exist!");
            return 1;
        }

        $columns = Schema::getColumnListing($table);
        $this->info("\nColumns in table '{$table}':");
        
        $rows = [];
        foreach ($columns as $column) {
            $rows[] = [
                'column' => $column,
                'type' => DB::getSchemaBuilder()->getColumnType($table, $column) ?: 'unknown',
            ];
        }

        $this->table(['Column', 'Type'], $rows);
        return 0;
    }
}
