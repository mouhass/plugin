<?php

namespace BatchJobs\BatchJobsBundle\Form;

use BatchJobs\BatchJobsBundle\Entity\Admin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, ['required'=>false])

            ->add('state',TextType::class,['required'=>false])
            ->add('actif',TextType::class,['required'=>false])
            ->add('listDestination',EntityType::class,['class' => Admin::class, 'choice_label' => 'email','multiple'=>true ])
            ->add('createdBy', EntityType::class, ['class' => Admin::class, 'choice_label' => 'name' ])
            ->add('nextDateExec',DateTimeType::class,['required'=>false]);

    }

}
