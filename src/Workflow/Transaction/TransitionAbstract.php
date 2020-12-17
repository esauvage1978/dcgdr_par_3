<?php


namespace App\Workflow\Transaction;


use App\Entity\Action;
use App\Repository\ActionRepository;
use App\Workflow\ActionCheck;

class TransitionAbstract implements Transition
{
    /**
     * @var Action
     */
    protected $action;



    /**
     * @var ActionCheck
     */
    protected $actionCheck;

    public function __construct(Action $item, ActionRepository $actionRepository)
    {
        $this->action=$item;
        $this->actionCheck=new ActionCheck($item, $actionRepository);
    }

    public function can(): bool
    {
        $this->check();
        return !$this->actionCheck->hasMessageError();
    }

    public function check()
    {

    }

    public function getExplains(): array
    {
        return [];
    }

    public function getCheckMessages(): array
    {
        $this->check();
        return $this->actionCheck->getMessages();
    }

    public function checkAll()
    {
        $this->actionCheck->checkName();
        $this->actionCheck->checkReference();
        $this->actionCheck->checkRegionStartAt();
        $this->actionCheck->checkRegionEndAt();
        $this->actionCheck->checkRegionStartAtBeforeRegionEnAt();
        $this->actionCheck->checkCadrage();
        $this->actionCheck->checkCorbeillePilotage();
        $this->actionCheck->checkCorbeilleCOTECHValidation();
        $this->actionCheck->checkCorbeilleCODIRValidation();
        $this->actionCheck->checkIndicators();
        $this->actionCheck->checkOrganismes();
        $this->actionCheck->checkDeploiement();
    }

    public function intialiseActionForTransition(bool $automate=false)
    {

    }
}
