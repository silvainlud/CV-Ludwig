<?php

namespace App\Form\CV;

use App\Entity\Main\CV\CompetenceCategorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetenceCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'cv.skills.skill-categories.attr.name',
            ])
            ->add('ordre', NumberType::class, [
                'label' => 'cv.skills.skill-categories.attr.order',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.action.btn.submit',
                'attr' => ['cancel_btn' => $options['cancel_btn']],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompetenceCategorie::class,
            'cancel_btn' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'form_competencecategorie';
    }
}
