<?php

namespace BatchJobs\BatchJobsBundle\Form;

use BatchJobs\BatchJobsBundle\Entity\Admin;
use BatchJobs\BatchJobsBundle\Entity\JobComposite;
use BatchJobs\BatchJobsBundle\Entity\JobCron;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateNewJobCompositeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('description',TextareaType::class,['required'=>false])
            ->add('name', TextType::class, ['required'=>false])
            ->add('emailadmin',TextType::class,['required'=>false])
            ->add('expression',TextType::class,['required'=>false])
            ->add('listSousJobs',EntityType::class,['class'=>JobCron::class,'multiple'=>true ,'required'=>false])
//            ->add('createdBy',EntityType::class,['class'=>Admin::class])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobComposite::class,
        ]);
    }
}
