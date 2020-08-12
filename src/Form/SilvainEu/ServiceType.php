<?php

namespace App\Form\SilvainEu;

use App\Entity\Main\SilvainEu\Service;
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

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'silvaineu.service.attr.name',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'silvaineu.service.attr.description',
                'required' => true,
            ])
            ->add('upload', FileType::class, [
                'label' => 'silvaineu.service.attr.image',
                'required' => false,
                'data_class' => UploadedFile::class,
                'constraints' => [
                    new Image(),
                    new File([
                        'maxSize' => '2M',
                    ]),
                ],
            ])
            ->add('link', TextType::class, [
                'label' => 'silvaineu.service.attr.link',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.action.btn.submit',
                'attr' => ['cancel_btn' => $options['cancel_btn']],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
            'cancel_btn' => false,
            'error_mapping' => ['image' => 'upload'],
        ]);
    }
}
