<?php

namespace David\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use David\TicketBundle\Entity\Reservation;

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bookingDate', DateType::class, [
                    'widget' => 'single_text',
                'years' => range(2017, 2027),
                'placeholder' => ['year' => '2017', 'month' => '01', 'day' => '23']
                // 'placeholder' => ['day' => '03', 'month' => '01', 'year' => '2017']
                ])
            ->add('visitType',   ChoiceType::class, [
                    'choices' => [
                        'Full-Day' => 'fullDay',
                        'Half-Day' => 'halfDay',
                        ],
                    'expanded' => \TRUE,
                    'multiple' => \FALSE,
                ])
            ->add('tickets',     CollectionType::class, [
                'entry_type'    => TicketType::class,
                'allow_add'     => true,
                'allow_delete'  => true,
                ])
            ->add('email',       TextType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Reservation::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'david_ticketbundle_reservation';
    }
}