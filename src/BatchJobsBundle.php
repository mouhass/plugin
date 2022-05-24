<?php


namespace BatchJobs\BatchJobsBundle;
use BatchJobs\BatchJobsBundle\DependencyInjection\BatchJobsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BatchJobsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     *
     * @return BatchJobsExtension
     */
    public function getContainerExtension()
    {
        $class = $this->getContainerExtensionClass();

        return new $class();
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensionClass()
    {
        return BatchJobsExtension::class;
    }
}
