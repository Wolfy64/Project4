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
    const HALF_DAY_TIME = 12; // Time in 24H to start an 1/2 Day

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bookingDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['value' => \date('Y-m-d')]])
            ->add('visitType', ChoiceType::class, [
                'choices' => $this->dayType(),
                'expanded' => \TRUE,
                'multiple' => \FALSE,])
            ->add('tickets', CollectionType::class, [
                'entry_type' => TicketType::class,
                'allow_add' => true,
                'allow_delete' => true])
            ->add('email', TextType::class, ['attr' => ['placeholder' => 'John.Doe@mail.com']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }

    public function dayType()
    {
        $dayType = ['Full-day' => 'fullDay', 'Half-day' => 'halfDay'];

        if (\date('H') > self::HALF_DAY_TIME) {
            $dayType = ['Half-day' => 'halfDay'];
        }

        return $dayType;
    }
}
