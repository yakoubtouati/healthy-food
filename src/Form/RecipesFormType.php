<?php

namespace App\Form;

use App\Entity\Recipes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class)
            ->add('category', ChoiceType::class, [
                'placeholder'=>'choissisez une catégorie',
                'choices'  => [
                    'Recettes Salées' => 'recettes salées',
                    'Recettes Sucrées' => 'recettes sucrées',
                    'Recettes Bébé' => 'recettes bébé',
                    'Jus Nature' => 'jus nature',
                ],
            ])
            ->add('imageFile',VichImageType::class,[
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image',
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => false,
                'imagine_pattern' => false,
                'asset_helper' => true,
            ])
            ->add('content',TextareaType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
