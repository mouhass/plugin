<?php

namespace BatchJobs\BatchJobsBundle\Form;

use BatchJobs\BatchJobsBundle\Entity\JobCompositeSearch;
use BatchJobs\BatchJobsBundle\Entity\JobCronSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobCompositeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'Le code']])

            ->add('name',TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'Le nom']])
            ->add('actif',TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'actif']])
            ->add('expression', TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'La fréquence ']])

            ->add('submit',SubmitType::class,['label'=>'Rechercher'] )
        ;    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobCompositeSearch::class,
            'method'=>'get',
            'csrf_protection'=>false,
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
