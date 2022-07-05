<?php

namespace App\Form;

use App\Entity\File;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType as FileTypeSymfony;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('document', FileTypeSymfony::class, [
                "mapped" => false,
                "label" => "Choisissez le document"
            ])
            ->add('category', ChoiceType::class, [
                'placeholder'       => '',
                'choices'           => $options['categories'],
                'choice_label'      => 'label',
                'choice_value'      => 'label',
                'label' => 'Choisissez la catégorie'
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
