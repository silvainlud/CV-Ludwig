<?php

namespace App\Tests\Entity\Main\SilvainEu;

use App\Entity\Main\SilvainEu\Service;
use App\Tests\Entity\EntityKernelTestCase;

/**
 * @internal
 * @coversNothing
 */
class ServiceTest extends EntityKernelTestCase
{
    public function testGetter()
    {
        $d = $this->GetEntity();
        $this->assertsHasErrors($d, 0);
        self::assertEquals('owncloud', $d->getName());
        self::assertEquals('description', $d->getDescription());
        self::assertEquals(10, $d->getImage());
        self::assertEquals('http://gotobac.net', $d->getLink());
        self::assertEquals('png', $d->getImageExtension());
        self::assertEquals('1-owncloud', $d->getSlug());
    }

    public function GetEntity(): Service
    {
        return (new Service())->setName('owncloud')
            ->setLink('http://gotobac.net')
            ->setDescription('description')
            ->setImage(10)
            ->setImageExtension('png')
            ->setSlug('1-owncloud');
    }

    public function testName()
    {
        $this->assertsHasErrors($this->GetEntity()->setName('owncloud'), 0);
        $this->assertsHasErrors($this->GetEntity()->setName('jesai12345jesai12345jesai12345j1'), 0);
        $this->assertsHasErrors($this->GetEntity()->setName('jesai12345jesai12345jesai12345j1+'), 1);
        $this->assertsHasErrors($this->GetEntity()->setName(''), 1);
    }

    public function testDescription()
    {
        $this->assertsHasErrors($this->GetEntity()->setDescription('description'), 0);
        $this->assertsHasErrors($this->GetEntity()->setDescription(''), 1);
    }

    public function testImage()
    {
        $this->assertsHasErrors($this->GetEntity()->setImage(10), 0);
        $this->assertsHasErrors($this->GetEntity()->setImage(null), 1);
    }

    public function testLink()
    {
        $this->assertsHasErrors($this->GetEntity()->setLink('http://gotobac.net'), 0);
        $this->assertsHasErrors($this->GetEntity()->setLink('http/gotoba'), 1);
        $this->assertsHasErrors($this->GetEntity()->setLink(''), 1);
    }

    public function testImageextension()
    {
        $this->assertsHasErrors($this->GetEntity()->setImageExtension('png'), 0);
        $this->assertsHasErrors($this->GetEntity()->setImageExtension('jesai12345'), 0);
        $this->assertsHasErrors($this->GetEntity()->setImageExtension('jesai12345+'), 1);
        $this->assertsHasErrors($this->GetEntity()->setImageExtension(''), 1);
    }

    public function testSlug()
    {
        $this->assertsHasErrors($this->GetEntity()->setSlug('1-owncloud'), 0);
        $this->assertsHasErrors($this->GetEntity()->setSlug('jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai123'), 0);
        $this->assertsHasErrors($this->GetEntity()->setSlug('jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai12345jesai123+'), 1);
    }
}
