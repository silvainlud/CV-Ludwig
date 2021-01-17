<?php

namespace App\Form\CV\Realisation;

use App\Entity\Main\CV\Realisation;
use App\Entity\Main\CV\Technologie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RealisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'cv.making.attr.name',
                'row_attr' => ['class' => 'form-col-12'],
            ])
            ->add('preface', TextareaType::class, [
                'label' => 'cv.making.attr.preface',
                'row_attr' => ['class' => 'form-col-12'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'cv.making.attr.description',
                'row_attr' => ['class' => 'form-col-12'],
            ])
            ->add('company', TextType::class, [
                'label' => 'cv.making.attr.company',
                'required' => false,
            ])
            ->add('technologies', EntityType::class, [
                'class' => Technologie::class,
                'multiple' => true,
                'choice_label' => 'name',
                'label' => 'cv.making.attr.technologies',
                'required' => false,
                'attr' => ['class' => 'select2'],
            ])
            ->add('dateRelease', DateTimeType::class, [
                'label' => 'cv.making.attr.dateToRelease',
                'required' => false,
            ])
            ->add('timeToMake', TextType::class, [
                'label' => 'cv.making.attr.timeToMake',
                'required' => false,
            ])
            ->add('link', TextType::class, [
                'label' => 'cv.making.attr.link',
                'required' => false,
            ])
            ->add('mainImage', RealisationImageMiniatureType::class, [
                'label' => false,
            ])
            ->add('public', CheckboxType::class, [
                'label' => 'cv.making.attr.public',
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
            'data_class' => Realisation::class,
            'cancel_btn' => false,
        ]);
    }
}
