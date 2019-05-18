<?php

namespace App\Form;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Title',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search by title'
                ]
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $this->entityManager->getRepository(Category::class)->findAll(),
                'choice_label' => function ($value) {
                    return $value;
                },
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Category',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('dateFrom', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control datetimepicker-input',
                    'data-target' => '#datetimepicker1',
                    'placeholder' => 'Select a date'
                ]
            ])
            ->add('dateTo', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control datetimepicker-input',
                    'data-target' => '#datetimepicker2',
                    'placeholder' => 'Select a date'
                ]
            ])
            ->add('priceFrom', NumberType::class, [
                'required' => false,
                'label' => 'Price From ($)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Price from',
                    'pattern' => '[0-9]+(\.[0-9][0-9]?)?'
                ]
            ])
            ->add('priceTo', NumberType::class, [
                'required' => false,
                'label' => 'Price To ($)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Price to',
                    'pattern' => '[0-9]+(\.[0-9][0-9]?)?'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filter',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->setMethod('get')
        ;
    }

}
