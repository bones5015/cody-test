<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Helpers\TableHelpers;


class TableTest extends TestCase
{
    public function test_it_chunks_tables(): void
    {
        $tableHelper = (new TableHelpers());
        $table = file_get_contents(storage_path('table.html'));

        $chunks = $tableHelper->chunk($table);

        $this->assertTrue(count($chunks) === 17);

        foreach ($chunks as $chunk) {
            $this->assertTrue(strlen($chunk) <= 1000);

            echo "Chunk Count: " . strlen($chunk) . "\n\n";
            echo $chunk . "\n\n";
        }
    }
}
