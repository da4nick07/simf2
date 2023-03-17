<?php

namespace App\Form;

use App\Class\CommentFilter;
use App\Enum\CommentStateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentsFilterFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('state', EnumType::class,[
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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Укажите статус комментария...',
                    ]),
                ]
            ])
            ->add('startDate', DateType::class, [
                'data' => new \DateTime('-2 days'),
                'widget' => 'single_text',
                'input'  => 'datetime',
                'label' => 'с:',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Укажите дату начала...',
                    ]),
                    new LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'Дата начала не может быть больше текущей даты...',
                    ]),
//                  НЕ РАБОТАЕТ....
//                    new LessThanOrEqual([
//                        'propertyPath' => 'endDate',
//                        'message' => 'Дата окончания периода не может быть меньше даты его начала...',
//                    ]),
                ]
            ])
            ->add('endDate', DateType::class, [
                'data' => new \DateTime('now'),
                'widget' => 'single_text',
                'input'  => 'datetime',
                'label' => 'по:',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Укажите дату окончания...',
                    ]),
                    new LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'Дата окончания не может быть больше текущей даты...',
                    ]),
//                    new GreaterThanOrEqual([
//                        'propertyPath' => "startDate",
//                        'message' => 'Дата окончания периода не может быть меньше даты его начала...',
//                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentFilter::class,
        ]);
    }

}
