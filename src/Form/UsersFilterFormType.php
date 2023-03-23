<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Enum\UserState;

class UsersFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('state', EnumType::class, [
                'class' => UserState::class,
                'choice_label' => fn ($choice) => match ($choice) {
                    UserState::All => UserState::All->value,
                    UserState::Enabled => UserState::Enabled->value,
                    UserState::NotEnabled  => UserState::NotEnabled->value,
                },
                'data' => UserState::NotEnabled,
                'label' => 'Статус:',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Укажите статус пользователя...',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

}
