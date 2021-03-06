<?php

namespace BatchJobs\BatchJobsBundle\Entity;
use BatchJobs\BatchJobsBundle\Repository\JobCompositeRepository;
use Cron\CronExpression;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass=JobCompositeRepository::class)
 */
class JobComposite extends Job
{


    /**
     * @ORM\OneToMany(targetEntity=JobCron::class, mappedBy="relationHistJobComp")
     * @ORM\JoinColumn(nullable=false)
     */
    private $historiqueSousJob;

    /**
     * @ORM\ManyToMany(targetEntity=JobCron::class, inversedBy="jobComposites")
     */
    private $listSousJobs;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailadmin;


    public function __construct()
    {
        $this->listSousJobs = new ArrayCollection();
        $this->historiqueSousJob = new ArrayCollection();

    }

    /**
     * @return Collection<int, JobCron>
     */
    public function getHistoriqueSousJob(): Collection
    {
        return $this->historiqueSousJob;
    }

    public function addHistoriqueSousJob(JobCron $historiqueSousJob): self
    {
        if (!$this->historiqueSousJob->contains($historiqueSousJob)) {
            $this->historiqueSousJob[] = $historiqueSousJob;
        }

        return $this;
    }

    public function removeHistoriqueSousJob(JobCron $historiqueSousJob): self
    {
        $this->historiqueSousJob->removeElement($historiqueSousJob);

        return $this;
    }



    /**
     * @return Collection<int, JobCron>
     */
    public function getListSousJobs(): Collection
    {
        return $this->listSousJobs;
    }

    public function addListSousJob(JobCron $listSousJob): self
    {
        if (!$this->listSousJobs->contains($listSousJob)) {
            $this->listSousJobs[] = $listSousJob;
        }

        return $this;
    }

    public function removeListSousJob(JobCron $listSousJob): self
    {
        $this->listSousJobs->removeElement($listSousJob);

        return $this;
    }




    public function nextDateCron(string $expression){

        $cron = new CronExpression($expression);
        return $cron->getNextRunDate()->format('i G j n w');
    }

    public function __toString()
    {
        return $this->getName();
    }




    public function getEmailadmin(): ?string
    {
        return $this->emailadmin;
    }

    public function setEmailadmin(string $emailadmin): self
    {
        $this->emailadmin = $emailadmin;


        return $this;
    }
}
