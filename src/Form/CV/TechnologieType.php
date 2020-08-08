<?php

namespace App\Form\CV;

use App\Entity\Main\CV\Technologie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class TechnologieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'cv.skills.technology.attr.name',
            ])
            ->add('upload', FileType::class, [
                'required' => false,
                'data_class' => UploadedFile::class,
                'label' => 'cv.skills.technology.attr.image',
                'constraints' => [
                    new Image(),
                    new File([
                        'maxSize' => '2M',
                    ]),
                ],
            ])
            ->add('color', TextType::class, [
                'label' => 'cv.skills.technology.attr.color',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'cv.skills.technology.attr.description',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.action.btn.submit',
                'attr' => ['cancel_btn' => $options['cancel_btn']],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Technologie::class,
            'cancel_btn' => false,
        ]);
    }
}
