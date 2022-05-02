<?php

namespace App\Entity\Main\CV;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class RealisationImageGallerie.
 */
#[Entity]
#[Table(name: 'CV_RealisationImageGallerie')]
class RealisationImageGallerie extends RealisationImage
{
    #[ManyToOne(targetEntity: 'App\Entity\Main\CV\Realisation', inversedBy: 'gallery')]
    #[JoinColumn]
    protected Realisation $realisations;
    public function getRealisation(): Realisation
    {
        return $this->realisations;
    }
    public function setRealisation(Realisation $realisations): self
    {
        $this->realisations = $realisations;

        return $this;
    }
    public function postUpdate(): void
    {
        $this->realisations->preUpdate();
    }
}
