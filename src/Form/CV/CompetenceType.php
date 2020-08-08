<?php

namespace App\Form\CV;

use App\Entity\Main\CV\Competence;
use App\Entity\Main\CV\CompetenceCategorie;
use App\Entity\Main\CV\CompetenceNiveau;
use App\Entity\Main\CV\Technologie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('niveau', EntityType::class, [
                'class' => CompetenceNiveau::class,
                'label' => 'cv.skills.skill.attr.level',
                'choice_label' => 'name',
            ])
            ->add('technologie', EntityType::class, [
                'class' => Technologie::class,
                'label' => 'cv.skills.skill.attr.technologie',
                'choice_label' => 'name',
            ]);
        if ($options['choose_categories']) {
            $builder->add('categorie', EntityType::class, [
                'class' => CompetenceCategorie::class,
                'label' => 'cv.skills.skill.attr.category',
                'choice_label' => 'name',
            ]);
        }
        $builder
            ->add('scolaire', CheckboxType::class, [
                'label' => 'cv.skills.skill.attr.scolaire',
                'required' => false,
            ])
            ->add('autoditacte', CheckboxType::class, [
                'label' => 'cv.skills.skill.attr.autoditacte',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.action.btn.submit',
                'attr' => ['cancel_btn' => $options['cancel_btn']],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competence::class,
            'cancel_btn' => false,
            'choose_categories' => true,
        ]);
    }
}
