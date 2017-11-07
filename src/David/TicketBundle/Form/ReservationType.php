<?php

namespace David\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use David\TicketBundle\Form\TicketType;


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
                        'Full-Day' => \TRUE,
                        'Half-Day' => \FALSE,
                        ],
                    'expanded' => \TRUE,
                    'multiple' => \FALSE,
                ])
            ->add('tickets',    TicketType::class, ['data_class' => null]);
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
