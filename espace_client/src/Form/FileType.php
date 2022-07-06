<?php

namespace App\Form;

use App\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType as FileTypeSymfony;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('document', FileTypeSymfony::class, [
                "mapped" => false,
                "label" => "Choisissez le document à ajouter",
                'attr'=>['class'=>'zone-upload']
            ])
            ->add('category', ChoiceType::class, [
                'choices'           => $options['categories'],
                'choice_label'      => 'label',
                'choice_value'      => 'label',
                'label' => 'Choisissez un dossier',
                'attr'=>['class'=>'choice-directory choice-field']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => File::class,
            'categories' => null
        ]);
    }
}
