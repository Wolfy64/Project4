<?php

namespace David\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

use David\TicketBundle\Entity\Guest;

class GuestType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lastName = 'Test';

        $builder
            ->add('firstName',   TextType::class)
            ->add('lastName',    TextType::class,[
                'attr' => ['placeholder' => $lastName]
                ])
            ->add('country',     TextType::class)
            ->add('dateOfBirth', BirthdayType::class,[
                // 'widget' => 'single_text',
                // 'input' => 'string',
                ]);


    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Guest::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'david_ticketbundle_guest';
    }



}
