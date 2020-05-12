<?php

namespace App\Form;

use App\Entity\Post;
use App\Form\DataTransformers\CategoryTransformer;
use App\Repository\CategoryRepository;
use Faker\Provider\ar_JO\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    protected CategoryRepository $categoryRepository;
    protected CategoryTransformer $categoryTransformer;

    public function __construct(CategoryRepository $categoryRepository, CategoryTransformer $categoryTransformer)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryTransformer = $categoryTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => "Titre de l'article",
                'attr' => [
                    'placeholder' => "Entrez le titre de l'article"
                ],
                'help' => "Soyez percuttant et concis"
            ])
            ->add('content', TextareaType::class, [
                'label' => "Contenu de l'article",
                'attr' => [
                    'placeholder' => "Faites envie avec une belle histoire"
                ],
                'help' => "Soignez la mise en forme"
            ])
            ->add('image', UrlType::class, [
                'label' => "Url de l'image",
                'attr' => [
                    'placeholder' => "Adresse URL de l'image"
                ],
                'help' => "Choisissez une image jolie"
            ])
            ->add('category', ChoiceType::class, [
                'label' => "Catégorie de l'article",
                'choices' => $this->getCategories()
            ]);


        $builder->get('category')->addModelTransformer($this->categoryTransformer);
    }

    protected function getCategories(): array
    {
        //1. data recovery
        $categories = $this->categoryRepository->findAll();

        //array assoc contruction
        $options = [];

        foreach ($categories as $category) {
            $options[$category->getTitle()] = $category->getId();
        }
        //return
        return $options;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
