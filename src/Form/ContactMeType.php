<?php

namespace App\Form;

use App\Utils\Helpers\Contact\ContactMe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactMeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => 'contact.attr.name',
            'required' => true,
        ])->add('email', EmailType::class, [
            'label' => 'contact.attr.email',
            'required' => true,
        ])->add('message', TextareaType::class, [
            'label' => 'contact.attr.message',
            'required' => true,
        ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.action.btn.submit',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactMe::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'contact_me';
    }
}
