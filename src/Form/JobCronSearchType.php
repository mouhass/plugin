<?php

namespace BatchJobs\BatchJobsBundle\Form;

use BatchJobs\BatchJobsBundle\Entity\JobCronSearch;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobCronSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'Le code']])
            ->add('name',TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'Le nom']])
            ->add('actif',NumberType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'les jobs actifs ']])
            ->add('command', TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'La commande exécutée ']])
            ->add('submit',SubmitType::class,['label'=>'Rechercher'] )
        ;    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobCronSearch::class,
            'method'=>'get',
            'csrf_protection'=>false,
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
