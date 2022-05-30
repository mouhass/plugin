<?php

namespace BatchJobs\BatchJobsBundle\Form;

use BatchJobs\BatchJobsBundle\Entity\Admin;
use BatchJobs\BatchJobsBundle\Entity\JobCron;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateNewJobCronType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, ['required'=>false])
            ->add('name',TextType::class,['required'=>false])
            ->add('expression', TextType::class,['required'=>false])
            ->add('emailadmincron',TextType::class,['required'=>false])
//            ->add('nextDateExec',DateTimeType::class,['required'=>false])
            ->add(
                            'scriptExec',
                            CommandChoiceType::class,
                            [
                                'label' => 'detail.command',
                                'required' => true,
                            ]
                        )

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobCron::class,
        ]);
    }


}
