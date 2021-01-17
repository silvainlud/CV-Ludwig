<?php

namespace App\Entity\Main\CV;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class RealisationImage.
 *
 * @ORM\Table(name="CV_RealisationImage")
 * @ORM\Entity
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"miniature": "RealisationImageMiniature", "gallery": "RealisationImageGallerie"})
 */
abstract class RealisationImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    protected string $id;

    /**
     * @ORM\Column(type="blob", nullable=false)
     *
     * @var resource|string $image
     */
    protected $image;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        if (!isset($this->id)) {
            return null;
        }

        return $this->id;
    }

    /**
     * @return resource|string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param resource|string $image
     *
     * @return $this
     */
    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return resource|string|null
     */
    public function getImageOrNull()
    {
        if (!isset($this->image)) {
            return null;
        }

        return $this->image;
    }

    public function getUpload(): ?UploadedFile
    {
        return null;
    }

    public function setUpload(UploadedFile $file): self
    {
        if (!empty($file->getPath())) {
            $_file = file_get_contents($file);
            if (false !== $_file) {
                $this->image = $_file;
            }
        }

        return $this;
    }
}