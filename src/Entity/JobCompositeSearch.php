<?php

namespace BatchJobs\BatchJobsBundle\Entity;

class JobCompositeSearch
{

    protected $code;
    protected $name;
    protected $actif;
    protected $expression;

    /**
 * @return mixed
 */
public function getCode()
{
    return $this->code;
}/**
 * @param mixed $code
 * @return JobCompositeSearch
 */
public function setCode($code)
{
    $this->code = $code;
    return $this;
}/**
 * @return mixed
 */
public function getName()
{
    return $this->name;
}/**
 * @param mixed $name
 * @return JobCompositeSearch
 */
public function setName($name)
{
    $this->name = $name;
    return $this;
}/**
 * @return mixed
 */
public function getActif()
{
    return $this->actif;
}/**
 * @param mixed $actif
 * @return JobCompositeSearch
 */
public function setActif($actif)
{
    $this->actif = $actif;
    return $this;
}/**
 * @return mixed
 */
public function getExpression()
{
    return $this->expression;
}/**
 * @param mixed $expression
 * @return JobCompositeSearch
 */
public function setExpression($expression)
{
    $this->expression = $expression;
    return $this;
}


}
