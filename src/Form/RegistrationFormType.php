<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'E-mail',
                    'required' => true,
                    'constraints' => new Length([
                        'min' => 3,
                        'max' => 30
                    ]),
                ])
            ->add('lastname', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Nom',
                    'required' => true,
                    'constraints' => new Length([
                        'min' => 3,
                        'max' => 30
                    ]),
                ])
            ->add('firstname', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Prénom',
                    'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 30
                ]),
                ])
            ->add('address', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Adresse'
                ])
            ->add('zipcode', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Code postal'
                ])
            ->add('city', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Ville'
                ])
            ->add('RGPDConsent', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => 'En \'inscrivant à ce site, j\'accepte les conditions'
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control'],
                'constraints' => [
                        new Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[.#?!@$%^&*-]).{2,}$/', "Il faut un mot de passe de 8 caractères avec 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial")
                ],
                'required' => true,

                'label' => 'Mot de passe'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
