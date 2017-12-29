<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bookingDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['value' => \date('Y-m-d')],
                'label' => 'Pick your day :'])
            ->add('visitType', ChoiceType::class, [
                'choices' => [
                    'Full-day' => 'fullDay',
                    'Half-day' => 'halfDay'],
                'expanded' => \TRUE,
                'multiple' => \FALSE,
                'label' => 'Select your ticket :'])
            ->add('tickets', CollectionType::class, [
                'entry_type' => TicketType::class,
                'allow_add' => true,
                'allow_delete' => true])
            ->add('email', TextType::class, [
                'attr' => [
                    'placeholder' => 'john.doe@mail.com'],
                'label' => 'Email Address'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
