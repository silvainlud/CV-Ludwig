<?php

namespace App\Entity\Main\CV;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RealisationImageMiniature.
 *
 * @ORM\Entity
 * @ORM\Table(name="CV_RealisationImageMiniature")
 */
class RealisationImageMiniature extends RealisationImage
{
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Main\CV\Realisation", inversedBy="mainImage")
     * @ORM\JoinColumn()
     */
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
}
