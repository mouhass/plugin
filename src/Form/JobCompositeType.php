<?php

namespace BatchJobs\BatchJobsBundle\Form;

use BatchJobs\BatchJobsBundle\Entity\Admin;
use BatchJobs\BatchJobsBundle\Entity\JobComposite;
use BatchJobs\BatchJobsBundle\Entity\JobCron;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobCompositeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code',TextType::class)
            ->add('name',TextType::class)
            ->add('state',TextType::class)
            ->add('actif',TextType::class)
            ->add('listDestination',EntityType::class,['class'=>Admin::class,'multiple'=>true])
            ->add('expression',TextType::class)
//            ->add('nextDateExec',DateTimeType::class)
            ->add('listSousJobs',EntityType::class,['class'=>JobCron::class,'multiple'=>true ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobComposite::class,
        ]);
    }
}
