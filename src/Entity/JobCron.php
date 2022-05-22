<?php

namespace BatchJobs\BatchJobsBundle\Entity;
use Cron\CronExpression;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use BatchJobs\BatchJobsBundle\Repository\JobCronRepository;
/**
 * @ORM\Entity(repositoryClass=JobCronRepository::class)
 */
class JobCron extends Job
{
    /**
     * @ORM\Column(type="string")
     */
     private $scriptExec;
     /**
      * @ORM\OneToMany(targetEntity=Historique::class, mappedBy="jobCronHist")
      */
     private $historiques;

    /**
     * @ORM\ManyToOne(targetEntity="BatchJobs\BatchJobsBundle\Entity\JobComposite" , inversedBy="historiqueSousJob")
     */
    private $relationHistJobComp;

    /**
     * @ORM\ManyToMany(targetEntity=JobComposite::class, mappedBy="listSousJobs")
     */
    private $jobComposites;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailadmincron;


     public function __construct()
     {
         $this->historiques = new ArrayCollection();
         $this->jobComposites = new ArrayCollection();

     }





     /**
      * @return Collection<int, Historique>
      */
     public function getHistoriques(): Collection
     {
         return $this->historiques;
     }

     public function addHistorique(Historique $historique): self
     {
         if (!$this->historiques->contains($historique)) {
             $this->historiques[] = $historique;
             $historique->setJobCronHist($this);
         }

         return $this;
     }

    /**
     * @return mixed
     */
    public function getScriptExec()
    {
        return $this->scriptExec;
    }

    /**
     * @param mixed $scriptExec
     * @return JobCron
     */
    public function setScriptExec($scriptExec)
    {
        $this->scriptExec = $scriptExec;
        return $this;
    }

     public function removeHistorique(Historique $historique): self
     {
         if ($this->historiques->removeElement($historique)) {
             // set the owning side to null (unless already changed)
             if ($historique->getJobCronHist() === $this) {
                 $historique->setJobCronHist(null);
             }
         }

         return $this;
     }

     public function __toString()
     {
         return $this->getScriptExec();
     }


    public function getRelationHistJobComp(): Collection
    {
        return $this->relationHistJobComp;
    }

    /**
     * @param mixed $relationHistJobComp
     * @return JobCron
     */
    public function setRelationHistJobComp($relationHistJobComp)
    {
        $this->relationHistJobComp = $relationHistJobComp;
        return $this;
    }

    /**
     * @return Collection<int, JobComposite>
     */
    public function getJobComposites(): Collection
    {
        return $this->jobComposites;
    }

    public function addJobComposite(JobComposite $jobComposite): self
    {
        if (!$this->jobComposites->contains($jobComposite)) {
            $this->jobComposites[] = $jobComposite;
            $jobComposite->addListSousJob($this);
        }

        return $this;
    }

    public function removeJobComposite(JobComposite $jobComposite): self
    {
        if ($this->jobComposites->removeElement($jobComposite)) {
            $jobComposite->removeListSousJob($this);
        }

        return $this;
    }





    public function nextDateCron(string $expression){

        $cron = new CronExpression($expression);
        return $cron->getNextRunDate()->format('i G j n w');
    }



    public function getEmailadmincron(): ?string
    {
        return $this->emailadmincron;
    }

    public function setEmailadmincron(string $emailadmincron): self
    {
        $this->emailadmincron = $emailadmincron;

        return $this;
    }



}
