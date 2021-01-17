<?php

namespace App\Form\CV\Realisation;

use App\Entity\Main\CV\RealisationImageGallerie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class RealisationImageGallerieType extends AbstractType
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
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.action.btn.submit',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RealisationImageGallerie::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'realisation_image_gallerie';
    }
}
