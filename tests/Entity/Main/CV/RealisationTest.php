<?php

namespace App\Tests\Entity\Main\CV;

use App\Entity\Main\CV\Realisation;
use App\Tests\Entity\EntityKernelTestCase;

/**
 * @internal
 * @coversNothing
 */
class RealisationTest extends EntityKernelTestCase
{
    public function testGetter()
    {
        self::assertEquals('name', self::GetEntity()->getName());
        self::assertEquals('ceci est un description', self::GetEntity()->getDescription());
        self::assertEquals('3 jours', self::GetEntity()->getTimeToMake());
        self::assertEquals('SilvainCorp', self::GetEntity()->getCompany());
        self::assertNotNull(self::GetEntity()->getDateRelease());
        self::assertEquals('https://silvain.eu', self::GetEntity()->getLink());
        self::assertNull(self::GetEntity()->getMainImage());
        self::assertCount(0, self::GetEntity()->getGallery());
        $this->assertsHasErrors(self::GetEntity(), 0);
    }

    public static function GetEntity(): Realisation
    {
        return (new Realisation())->setName('name')->setDescription('ceci est un description')
            ->setTimeToMake('3 jours')->setCompany('SilvainCorp')->setDateRelease(new \DateTime())
            ->setLink('https://silvain.eu');
    }

    public function testName()
    {
        $this->assertsHasErrors(self::GetEntity()->setName('test'), 0);
        $this->assertsHasErrors(self::GetEntity()->setName(''), 1);
        $this->assertsHasErrors(self::GetEntity()->setName('azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345az12v'), 0);
        $this->assertsHasErrors(self::GetEntity()->setName('azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345azert12345az12v+'), 1);
    }

    public function testDescription()
    {
        $this->assertsHasErrors(self::GetEntity()->setDescription('test'), 0);
        $this->assertsHasErrors(self::GetEntity()->setDescription(''), 1);
    }

    public function testLink()
    {
        $this->assertsHasErrors(self::GetEntity()->setLink('https://google.fr'), 0);
        $this->assertsHasErrors(self::GetEntity()->setLink('gegege'), 1);
    }

    public function testCompany()
    {
        $this->assertsHasErrors(self::GetEntity()->setCompany('company'), 0);
        $this->assertsHasErrors(self::GetEntity()->setCompany('azert12345azert12345azert12345a1'), 0);
        $this->assertsHasErrors(self::GetEntity()->setCompany('azert12345azert12345azert12345a1+'), 1);
    }

    public function testTimeToMake()
    {
        $this->assertsHasErrors(self::GetEntity()->setTimeToMake('company'), 0);
        $this->assertsHasErrors(self::GetEntity()->setTimeToMake('azert12345azert12345azert12345a1'), 0);
        $this->assertsHasErrors(self::GetEntity()->setTimeToMake('azert12345azert12345azert12345a1+'), 1);
    }
}
