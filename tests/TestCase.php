<?php

namespace Tests;

use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    // Every feature test starts with the same published content snapshot.
    // Admin credentials remain optional and are never seeded in tests.
    protected $seed = true;

    protected $seeder = ContentSeeder::class;
}
