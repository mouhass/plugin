<?php

namespace BatchJobs\BatchJobsBundle\Entity;

class JobCronSearch
{
    private $code;
    private $command;

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
