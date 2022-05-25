<?php


namespace BatchJobs\BatchJobsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('batch_jobs');
        $rootNode = $treeBuilder->root('batch_jobs');
        return $treeBuilder;
    }
}
