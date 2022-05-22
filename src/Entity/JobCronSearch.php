<?php

namespace BatchJobs\BatchJobsBundle\Entity;

class JobCronSearch
{
    private $code;
    private $command;
    private $name;
    private $actif;

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     * @return JobCronSearch
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return JobCronSearch
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }


    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     * @return JobCronSearch
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }






}
