<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/TestCase.php';

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Webong\WebProxy\Tests\TestCase;

uses(TestCase::class, LazilyRefreshDatabase::class)->in('Feature');
uses(TestCase::class)->in('Unit');
