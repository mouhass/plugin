<?php


namespace BatchJobs\BatchJobsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BatchJobsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
       // var_dump("we are alive");die;
     }
}
