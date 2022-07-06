<?php

namespace App\Form;

use App\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType as FileTypeSymfony;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ImportUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('document', FileTypeSymfony::class, [
                "mapped" => false,
                "label" => "Veuillez choisir un fichier csv conforme au fichier d'exemple",
                'attr'=>['class'=>'zone-upload']
            ])
        ;
    }
}
