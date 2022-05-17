<?php

namespace BatchJobs\BatchJobsBundle\Message;

class LogCommand
{
    private $codeCommand;
    private $nameCommand;
    private $dernierSousJob;
    private $idJobCron;
    public function __construct(string $nameCommand,string $idJobCron,string $codeCommand ,string $dernierSousJob)
    {
        $this->codeCommand = $codeCommand;
        $this->nameCommand = $nameCommand;
        $this->dernierSousJob = $dernierSousJob;
        $this->idJobCron = $idJobCron;
    }

    /**
     * @return string
     */
    public function getNameCommand(): string
    {
        return $this->nameCommand;
    }

    /**
     * @param string $nameCommand
     * @return LogCommand
     */
    public function setNameCommand(string $nameCommand): LogCommand
    {
        $this->nameCommand = $nameCommand;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodeCommand(): string
    {
        return $this->codeCommand;
    }

    /**
     * @param string $codeCommand
     * @return LogCommand
     */
    public function setCodeCommand(string $codeCommand): LogCommand
    {
        $this->codeCommand = $codeCommand;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdJobCron(): string
    {
        return $this->idJobCron;
    }

    /**
     * @param string $idJobCron
     * @return LogCommand
     */
    public function setIdJobCron(string $idJobCron): LogCommand
    {
        $this->idJobCron = $idJobCron;
        return $this;
    }



    /**
     * @return string
     */
    public function getDernierSousJob(): string
    {
        return $this->dernierSousJob;
    }

    /**
     * @param string $dernierSousJob
     * @return LogCommand
     */
    public function setDernierSousJob(string $dernierSousJob): LogCommand
    {
        $this->dernierSousJob = $dernierSousJob;
        return $this;
    }




}
