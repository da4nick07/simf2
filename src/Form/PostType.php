<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Заголовок* :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите заголовок...',
                    ])
                ]
            ])
            ->add('intro', TextareaType::class, [
                'label' => 'Краткое описание* :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите краткое описание...',
                    ])
                ]
            ])
            ->add('body', TextareaType::class, [
                'label' => 'Текст статьи* :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите текст статьи...',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
