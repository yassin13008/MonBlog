<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
      ->add('firstname', TextType::class, [
        'required' => true,
        'label' => 'Prénom',
        'constraints' => [
            new NotBlank()
        ]])

       ->add('lastname', TextType::class, ['label' => 'Nom'])
       ->add('email', EmailType::class, ['label' => 'Email'])
       ->add('plainPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'mapped' => false,
        'invalid_message' => 'La confirmation du mot de passe ne correspond pas',
        'required' => true,
        'first_options'  => ['label' => 'Mot de passe'],
        'second_options' => ['label' => 'Mot de passe (confirmation)'],
        'constraints' => [
            new NotBlank(),
            new Length([
              'min' => 8, 
              'minMessage' => 'user.plainPassword.length.min'])
        ]
            ]);
     
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
