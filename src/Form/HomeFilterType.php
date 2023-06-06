<?php

namespace App\Form;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class HomeFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchField', SearchType::class, [
                'required' => false,
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'query_builder' => function(CampusRepository $campusRepository) {
                    $qb = $campusRepository->createQueryBuilder('c');
                    return $qb;
                },
                'placeholder' => 'Campus',
                'label' => 'Campus :'
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Starting :',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Ending :',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('isOrganised', CheckboxType::class, [
                'label' => 'I created this event'
            ])
            ->add('isSubed', CheckboxType::class, [
                'label' => "I'm part of this event"
            ])
            ->add('isNotSubed', CheckboxType::class, [
                'label' => "I'm not part of this event"
            ])
            ->add('isOver', CheckboxType::class, [
                'label' => 'The event is over'
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
