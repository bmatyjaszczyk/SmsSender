<?php

namespace App\Form;

use App\Entity\Sms as SmsEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class Sms extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipient', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a number.',
                    ]),
                    new Regex([
                        'message' => 'Please enter valid mobile number.',
                        'pattern' => '/^[+][0-9]{1,3}[0-9]{5,13}$/'
                    ])
                ],
            ])
            ->add('body', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a number.',
                    ]),
                    new Length([
                        'max' => 140
                    ])
                ],
            ])
//            ->add('created_at', HiddenType::class)
//            ->add('sender', HiddenType::class)
//            ->add('status', HiddenType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SmsEntity::class,
        ]);
    }
}
