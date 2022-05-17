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
     * @ORM\Column(type="string")
     */
    private $expression;

    /**
     * @ORM\ManyToOne(targetEntity="BatchJobs\BatchJobsBundle\Entity\JobComposite" , inversedBy="historiqueSousJob")
     */
    private $relationHistJobComp;

    /**
     * @ORM\ManyToMany(targetEntity=JobComposite::class, mappedBy="listSousJobs")
     */
    private $jobComposites;

    /**
     * @ORM\ManyToMany(targetEntity=Admin::class, inversedBy="jobCronCreated")
     */
    private $listDesination;

    /**
     * @ORM\Column(type="boolean")
     */
    public $actif;


    /**
     * @ORM\Column(type="string")
     */
    private $state;

    /**
     * @ORM\Column(type="integer",unique=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailadmincron;

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     * @return JobCron
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
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
     * @return JobCron
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
        return $this;
    }

     public function __construct()
     {
         $this->historiques = new ArrayCollection();
         $this->jobComposites = new ArrayCollection();
         $this->listDesination = new ArrayCollection();
     }

    /**
     * @return mixed
     */




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

    /**
     * @return Collection<int, Admin>
     */
    public function getListDesination(): Collection
    {
        return $this->listDesination;
    }

    public function addListDesination(Admin $listDesination): self
    {
        if (!$this->listDesination->contains($listDesination)) {
            $this->listDesination[] = $listDesination;
        }

        return $this;
    }

    public function removeListDesination(Admin $listDesination): self
    {
        $this->listDesination->removeElement($listDesination);

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function nextDateCron(string $expression){

        $cron = new CronExpression($expression);
        return $cron->getNextRunDate()->format('i G j n w');
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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
