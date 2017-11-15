<?php

namespace David\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use David\TicketBundle\Form\GuestType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bookingDate',  DateType::class,[
                'widget' => 'single_text'
                ])
            ->add('guest',        GuestType::class)
            ->add('reducedPrice', CheckboxType::class,[
                'required' => false
                ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'David\TicketBundle\Entity\Ticket' // Default
            // 'data_class' => Tag::class, // Doc
            // 'data_class' => TicketType::class,
            // 'data_class' => 'David\TicketBundle\Form\TicketType',
            // David\TicketBundle\Form\TicketType
            // David\TicketBundle\Entity\Ticket
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'david_ticketbundle_ticket';
    }


}
