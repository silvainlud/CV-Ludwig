<?php

namespace App\Form\CV;

use App\Entity\Main\CV\Technologie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
            ->add('link', TextType::class, [
                'label' => 'cv.skills.technology.attr.url',
                'required' => false,
            ])
            ->add('linkedTechonologies', EntityType::class, [
                'class' => Technologie::class,
                'multiple' => true,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'cv.skills.technology.attr.link',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'cv.skills.technology.attr.description',
                'row_attr' => ['class' => 'form-col-12'],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.action.btn.submit',
                'attr' => ['cancel_btn' => $options['cancel_btn']],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Technologie::class,
            'cancel_btn' => false,
            'error_mapping' => ['image' => 'upload'],
        ]);
    }
}
