<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Renginio pavadinimas'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Renginio aprašymas'
                ]
            ])
            ->add('date', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-control datetimepicker-input',
                    'data-target' => '#datetimepicker1',
                    'placeholder' => 'Renginio data'
                ],
                'widget' => 'single_text',
                'html5' => false
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Renginio mokestis'
                ]
            ])
            ->add('location', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Renginio vieta'
                ]
            ])
            ->add('category', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'placeholder' => 'Pasirinkite kategoriją',
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('photo', FileType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            /* 'data_class' => Category::class, */
        ]);
    }
}
