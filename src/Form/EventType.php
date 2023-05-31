<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Location;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['label' => 'Event name'])
            ->add('dateTimeStart',DateTimeType::class, ['html5'=>true,'widget'=>'single_text'])
            ->add('dateLimitRegistration',DateType::class, ['html5'=>true,'widget'=>'single_text'])
            ->add('numMaxRegistration')
            ->add('duration', IntegerType::class,['label' => 'Duration in minutes'])
            ->add('infoEvent', TextareaType::class, ['label' => 'Description and information'])
            ->add('campus', EntityType::class,['class'=>Campus::class,'choice_label'=>'name'])
            ->add('city', EntityType::class, ['mapped'=>false,'class'=>City::class,'choice_label'=>'name'])
            ->add('location', EntityType::class, ['mapped'=>false,'class'=>Location::class,'choice_label'=>'name'])
            ->add('street', TextType::class, ['mapped'=>false])
            ->add('zipcode', TextType::class, ['mapped'=>false,'label' => 'Zip code'])
            ->add('latitude', TextType::class, ['mapped'=>false])
            ->add('longitude', TextType::class, ['mapped'=>false])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}