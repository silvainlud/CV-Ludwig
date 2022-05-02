<?php

namespace App\Entity\Main\CV;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class RealisationImageMiniature.
 */
#[Entity]
#[Table(name: 'CV_RealisationImageMiniature')]
class RealisationImageMiniature extends RealisationImage
{
    #[OneToOne(inversedBy: 'mainImage', targetEntity: 'App\Entity\Main\CV\Realisation')]
    #[JoinColumn]
    protected Realisation $realisation;
    public function getRealisation(): Realisation
    {
        return $this->realisation;
    }
    public function setRealisation(Realisation $realisation): self
    {
        $this->realisation = $realisation;
        $realisation->setMainImage($this);

        return $this;
    }
    public function postUpdate(): void
    {
        $this->realisation->preUpdate();
    }
}
