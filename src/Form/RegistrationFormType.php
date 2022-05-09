<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label' => 'Full name :',
                'attr' => [
                    'autofocus' => 1,
                    'class' => 'form-control',
                    'placeholder' => 'John Doe'
                ]
            ])

            ->add('username', TextType::class, [
                'label' => 'Username :',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'johndoe'
                ]
            ])

            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'The e-mail fields must match.',
                'first_options' => [
                    'label' => 'Active e-mail :',
                    'attr'  => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder' => 'john@doe.com',
                    ]
                ],
                'second_options' => [
                    'label' => 'Repeat e-mail :',
                    'attr'  => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder' => 'john@doe.com'
                    ]
                ],

                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter username',
                    ]),

                    new Email([
                        'message' => 'E-mail not valid',
                    ]),

                    new Length([
                        'max' => 32,
                    ]),
                ],
            ])

            ->add('plainPassword', PasswordType::class, [
                'label' => 'New password :',
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                    'placeholder' => 'Unique password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),

                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
