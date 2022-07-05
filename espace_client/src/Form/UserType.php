<?php

namespace App\Form;

use App\Entity\User;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail',
                'attr'=>['class'=>'input']
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required'=>false,
                'attr'=>['class'=>'input']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required'=>false,
                'attr'=>['class'=>'input']
            ])
            ->add('isAdmin', CheckboxType::class, [
                    'label' => 'Est un administrateur',
                    'required'=>false,
                    'data' => false,
                ]
            )
            ->add('enterprise', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'required'=>true,
                'attr'=>['class'=>'input-semi-long']
            ])
            ->add('civility', ChoiceType::class, [
                'label' => 'Civilité',
                'choices'  => [
                    'Monsieur' => 'Monsieur',
                    'Madame' => 'Madame',
                    'Autre' => 'Autre',
                ],
                'attr'=>['class'=>'choice-field']
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
