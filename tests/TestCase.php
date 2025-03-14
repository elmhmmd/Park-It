<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}