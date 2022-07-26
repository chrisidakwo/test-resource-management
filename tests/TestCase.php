<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate --seed');
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');

        parent::tearDown();
    }
}
