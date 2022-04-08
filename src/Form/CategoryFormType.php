<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent  $event) {
                $category = $event->getData();
                $form = $event->getForm();


                //check if the product object is "new" 
                //if no data  is passed to the form, the data is "null".
                //this should be considered a new "Product"
                if (!$category || null === $category->getId()) {
                    $form->add('save', SubmitType::class, ['label' => 'New Category']);
                }
            })
            ->add('title', TextType::class)
           
    
            ->add('save', SubmitType::class, ['label' => 'Edit Category']);
    }


    public function buildFormMulti(FormBuilderInterface $builder, array $options){
        $builder->add('title');
    }





    public function configureureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class
        ]);
    }
}
