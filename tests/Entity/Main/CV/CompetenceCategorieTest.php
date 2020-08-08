<?php

namespace App\Tests\Entity\Main\CV;

use App\Entity\Main\CV\CompetenceCategorie;
use App\Tests\Entity\EntityKernelTestCase;

/**
 * @internal
 * @coversNothing
 */
class CompetenceCategorieTest extends EntityKernelTestCase
{
    public function testGetter()
    {
        $this->assertsHasErrors($this->GetEntity(), 0);
        self::assertEquals('categorie', $this->GetEntity()->getName());
        self::assertEquals(0, $this->GetEntity()->getOrdre());
    }

    public function testName()
    {
        $this->assertsHasErrors($this->GetEntity()->setName('categorie'), 0);
        $this->assertsHasErrors($this->GetEntity()->setName('jesai12345jesai12345jesai12345jesai12345jesai12345jesai123451234'), 0);
        $this->assertsHasErrors($this->GetEntity()->setName('jesai12345jesai12345jesai12345jesai12345jesai12345jesai123451234+'), 1);
        $this->assertsHasErrors($this->GetEntity()->setName(''), 1);
    }

    public function testOrder()
    {
        $this->assertsHasErrors($this->GetEntity()->setOrdre(0), 0);
        $this->assertsHasErrors($this->GetEntity()->setOrdre(-1), 1);
        $this->assertsHasErrors($this->GetEntity()->setOrdre(null), 1);
    }

    private function GetEntity(): CompetenceCategorie
    {
        return (new CompetenceCategorie())->setName('categorie')
            ->setOrdre(0);
    }
}
