<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @coversNothing
 */
class EntityKernelTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public function assertsHasErrors($u, int $number = 0)
    {
        $error = self::$container->get('validator')->validate($u);
        $this->assertCount($number, $error);
    }
}
