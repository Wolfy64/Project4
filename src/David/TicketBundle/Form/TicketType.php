<?php

namespace David\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use David\TicketBundle\Form\GuestType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use David\TicketBundle\Entity\Ticket;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('guest',        GuestType::class, [
                'label' => false
            ])
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
            'data_class' => Ticket::class
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
