<?php

namespace App\Form;

use App\Enum\CommentStateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentsFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_state', EnumType::class,[
                'class' => CommentStateType::class,
                'choice_label' => fn ($choice) => match ($choice) {
                    CommentStateType::DRAFT => $choice->getName(),
                    CommentStateType::SUBMITTED => $choice->getName(),
                    CommentStateType::SPAM => $choice->getName(),
                    CommentStateType::HAM => $choice->getName(),
                    CommentStateType::REJECTED  => $choice->getName(),
                    CommentStateType::PUBLISHED  => $choice->getName(),
                },

                'data' => CommentStateType::SUBMITTED,
                'label' => 'Статус:',
//                'label_attr' => [ 'text-align' => 'right'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Укажите статус комментария...',
                    ])
                ]
            ])
            ->add('_startDate', DateType::class, [
                'data' => new \DateTime('-2 days'),
                'widget' => 'single_text',
                'input'  => 'datetime',
                'label' => 'с:',
//                'label_attr' => [ 'text-align' => 'right'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Укажите дату начала...',
                    ])
                ]
            ])
            ->add('_endDate', DateType::class, [
                'data' => new \DateTime('now'),
                'widget' => 'single_text',
                'input'  => 'datetime',
                'label' => 'по:',
//                'label_attr' => [ 'text-align' => 'right'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Укажите дату окончания...',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

}
