<?php

namespace App\Entity\Main\CV;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RealisationImageGallerie.
 *
 * @ORM\Entity
 * @ORM\Table(name="CV_RealisationImageGallerie")
 */
class RealisationImageGallerie extends RealisationImage
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\CV\Realisation", inversedBy="gallery")
     * @ORM\JoinColumn
     */
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

    public function preUpdate(): void
    {
        parent::preUpdate();
        $this->realisations->preUpdate();
    }
}
