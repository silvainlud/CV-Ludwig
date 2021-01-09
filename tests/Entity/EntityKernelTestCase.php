<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $error = self::$container->get(ValidatorInterface::class)->validate($u);
        $this->assertCount($number, $error);
    }
}
