<?php

namespace BatchJobs\BatchJobsBundle\Service;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class CommandChoiceList.
 *
 * @author  Julien Guyon <julienguyon@hotmail.com>
 */
class CommandParser
{
    /**
     * @var KernelInterface
     */
    private $kernel;


    /**
     * CommandParser constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel, array $excludedNamespaces = [], array $includedNamespaces = [])
    {
        $this->kernel = $kernel;
    }

    /**
     * Execute the console command "list" with XML output to have all available command.
     *
     * @return array
     */
    public function getCommands()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => 'list',
                '--format' => 'xml',
            ]
        );

        $output = new StreamOutput(fopen('php://memory', 'w+'));
        $application->run($input, $output);
        rewind($output->getStream());

        return $this->extractCommandsFromXML(stream_get_contents($output->getStream()));
    }

    /**
     * Extract an array of available Symfony command from the XML output.
     *
     * @param $xml
     *
     * @return array
     */
    private function extractCommandsFromXML($xml)
    {
        if ('' == $xml) {
            return [];
        }

        $node = new \SimpleXMLElement($xml);
        $commandsList = [];

        foreach ($node->namespaces->namespace as $namespace) {
            $namespaceId = (string) $namespace->attributes()->id;

            foreach ($namespace->command as $command) {
                $commandsList[$namespaceId][(string) $command] = (string) $command;
            }
        }

        return $commandsList;
    }
}
