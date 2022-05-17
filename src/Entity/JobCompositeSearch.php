<?php

namespace BatchJobs\BatchJobsBundle\Entity;

class JobCompositeSearch
{

    protected $codecomposite;
    protected $nameSousJob;

    /**
     * @return mixed
     */
    public function getNameSousJob()
    {
        return $this->nameSousJob;
    }

    /**
     * @param mixed $nameSousJob
     * @return JobCompositeSearch
     */
    public function setNameSousJob($nameSousJob)
    {
        $this->nameSousJob = $nameSousJob;
        return $this;
    }
    private $expression;

    /**
     * @return mixed
     */
    public function getCodecomposite()
    {
        return $this->codecomposite;
    }


    public function setCodecomposite($codecomposite)
    {
        $this->codecomposite = $codecomposite;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param mixed $expression
     * @return JobCompositeSearch
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
        return $this;
    }



}
