<?php

namespace Tests\Shared;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IntegrationTestCase extends KernelTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $output = null;
        exec('rm -rf ./var/data-test.db', $output);
        @exec('bin/console doctrine:schema:drop --env=test > /dev/null 2>&1');
        @exec('bin/console doctrine:schema:create --env=test > /dev/null 2>&1');
    }
}
