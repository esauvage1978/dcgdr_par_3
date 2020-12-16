<?php


namespace App\Workflow;


use App\Entity\Action;
use App\Repository\ActionRepository;

class WorkflowActionTransitionManager
{
    /**
     * @var Action
     */
    private $action;

    /**
     * @var ActionRepository
     */
    private $actionRepository;


    /**
     * @var string
     */
    private $transition;


    public function __construct(Action $action, ActionRepository $actionRepository, string $transition = '')
    {
        $this->action=$action;
        $this->transition=$transition;
        $this->actionRepository= $actionRepository;
    }

    public function intialiseActionForTransition(string $content, bool $automate=false)
    {
        $this->action->setStateAt(new \DateTime());
        $this->action->setStateContent($content);

        $object = __NAMESPACE__ . '\Transaction\Transition' . ucfirst( $this->transition);
        $instance=new $object($this->action, $this->actionRepository);
        $instance->intialiseActionForTransition($automate);
    }

    public function can(): bool
    {
        $object = __NAMESPACE__ . '\Transaction\Transition' . ucfirst( $this->transition);
        $instance=new $object($this->action, $this->actionRepository);
        return $instance->can();
    }
}