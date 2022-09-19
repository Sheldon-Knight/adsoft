<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    /**
     * Setup the test suite.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // $this->withoutVite();
    }
}
