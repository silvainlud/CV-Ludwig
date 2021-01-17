<?php

namespace App\Form\CV\Realisation;

use App\Entity\Main\CV\RealisationImageMiniature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class RealisationImageMiniatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('upload', FileType::class, [
                'label' => 'cv.making.attr.mainImage',
                'required' => false,
                'constraints' => [
                    new Image(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RealisationImageMiniature::class,
        ]);
    }
}
