<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostFormType extends AbstractType
{

    private $slugger;
    private $user;

    public function __construct(SluggerInterface $slugger, Security $security){
        $this->slugger = $slugger;
        $this->user = $security->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent  $event) {
                $post = $event->getData();
                $form = $event->getForm();


                //check if the product object is "new" 
                //if no data  is passed to the form, the data is "null".
                //this should be considered a new "Product"
                if (!$post || null === $post->getId()) {
                    $form->add('save', SubmitType::class, ['label' => 'New Post']);
                }
            })
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('Categories', EntityType::class, [
                'class' => Category::class,
                'multiple' => true, 
                'choice_label' => "title",
                'by_reference' => false, 
                'expanded' => true, 
            ])
            ->add('date_publication', DateType::class, [
                'label' => 'Date publication',
                'help' =>'help.post_publication',
                'widget' => 'single_text',
                ])
            
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
                
                /** @var Post */
                $post = $event->getData();

                if(null !== $post->getTitle()){
                    $post->setSlug($this->slugger->slug($post->getTitle())->lower() );
                }

                if($this->user !== null){
                    $post->setAuthor($this->user); 
                }

            })
            ->add('save', SubmitType::class, ['label' => 'Edit Post']);
    }
    public function configureureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostFormType::class
        ]);
    }
}
