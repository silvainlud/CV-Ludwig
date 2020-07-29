<?php

namespace App\Tests\Entity\Main\CV;

use App\Entity\Main\CV\Technologie;
use App\Tests\Entity\EntityKernelTestCase;

/**
 * @internal
 * @coversNothing
 */
class TechnologieTest extends EntityKernelTestCase
{
    public function testGetter()
    {
        $this->assertsHasErrors($this->GetEntity(), 0);
        self::assertEquals('Html', $this->GetEntity()->getName());
        self::assertEquals('Description', $this->GetEntity()->getDescription());
        self::assertEquals('image', $this->GetEntity()->getImage());
        self::assertEquals('png', $this->GetEntity()->getImageExtension());
        self::assertEquals('#000000', $this->GetEntity()->getColor());
    }

    public function testValidatorName()
    {
        $this->assertsHasErrors($this->GetEntity()->setName('Html'), 0);
        $this->assertsHasErrors($this->GetEntity()->setName('jesai12345jesai12345jesai12345jesai12345jesai12345jesai123451234'), 0);
        $this->assertsHasErrors($this->GetEntity()->setName('jesai12345jesai12345jesai12345jesai12345jesai12345jesai123451234+'), 1);
        $this->assertsHasErrors($this->GetEntity()->setName(''), 1);
    }

    public function testValidatorDescription()
    {
        $this->assertsHasErrors($this->GetEntity()->setName('Description'), 0);
        $this->assertsHasErrors($this->GetEntity()->setName(''), 1);
    }

    public function testValidatorImageExtension()
    {
        $this->assertsHasErrors($this->GetEntity()->setImageExtension('png'), 0);
        $this->assertsHasErrors($this->GetEntity()->setImageExtension('jesai12345'), 0);
        $this->assertsHasErrors($this->GetEntity()->setImageExtension('jesai12345+'), 1);
    }

    public function testValidatorColor()
    {
        $this->assertsHasErrors($this->GetEntity()->setColor('png'), 0);
        $this->assertsHasErrors($this->GetEntity()->setColor('jesai12345jesai1234512345'), 0);
        $this->assertsHasErrors($this->GetEntity()->setColor('jesai12345jesai1234512345+'), 1);
    }

    private function GetEntity(): Technologie
    {
        return (new Technologie())->setName('Html')
            ->setDescription('Description')
            ->setImage('image')
            ->setImageExtension('png')
            ->setColor('#000000');
    }
}
