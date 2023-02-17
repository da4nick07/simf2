<?php

namespace App\Form;

use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsersFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('_state0')

            ->add('_state', IntegerType::class, [
                'label' => 'Статус пользователя :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите код статуса...',
                    ])
                ]
            ])

//            ->add('submit', SubmitType::class)
//            ->add('post')
//            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

}
