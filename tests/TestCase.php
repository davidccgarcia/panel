<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertDatabaseEmpty($table, $connection = null)
    {
        $total = $this->getConnection($connection)->table($table)->count();
        $this->assertSame(0, $total, sprintf(
            "Failed asserting the table [%s] is empty. %s %s found.", $table, $total, str_plural('row', $total)
        ));
    }

    protected function assertDatabaseCount($table, $connection = null)
    {
        $total = $this->getConnection($connection)->table($table)->count();
        $this->assertSame(1, $total, sprintf(
            "Failed asserting the count is equal to 1 in the table [%s]. %s %s found.",
            $table, $total, str_plural('row', $total)
        ));
    }
}
