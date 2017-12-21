<?php

namespace App\Form;

use App\Entity\Guest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class GuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => ['placeholder' => 'First name'],
                'label' => false])
            ->add('lastName', TextType::class, [
                'attr' => ['placeholder' => 'Last name'],
                'label' => false])
            ->add('country', CountryType::class,[
                'preferred_choices' => ['FR','GB','ES','DE','IT'],
                'label' => 'Select Your Country :'])
            ->add('dateOfBirth', BirthdayType::class, [
                'widget' => 'single_text',
                'label' => 'Enter Your Date Of Birth :']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Guest::class,
        ]);
    }
}
