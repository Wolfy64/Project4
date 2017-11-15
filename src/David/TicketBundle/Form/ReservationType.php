<?php

namespace David\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',      TextType::class)
            ->add('visitType',  ChoiceType::class, [
                    'choices' => [
                        'Full-Day' => 'fullDay',
                        'Half-Day' => 'halfDay',
                        ],
                    'expanded' => \TRUE,
                    'multiple' => \FALSE,
                ])

            ->add('tickets',    CollectionType::class, [
                'entry_type'    => TicketType::class,
                'entry_options' => array('label' => false),
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'David\TicketBundle\Entity\Reservation'
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
