<?php

namespace App\Service;

use App\Dto\UserDto;
use App\Entity\User;
use App\Dto\ActionDto;
use App\Security\Role;
use App\Dto\DeployementDto;
use App\Security\CurrentUser;
use App\Workflow\WorkflowData;

class DeployementMakerDto
{


    public const DEPLOYEMENT_WITHOUT_WRITERS = "deployement_without_writers";
    public const DEPLOYEMENT_WITHOUT_JALON_WRITERS = "deployement_without_jalon_writers";
    public const DEPLOYEMENT_JALON_TO_LATE_WRITERS = "deployement_jalon_to_late_writers";
    public const DEPLOYEMENT_JALON_TO_NEAR_WRITERS = "deployement_jalon_to_near_writers";
    public const DEPLOYEMENT_JALON_COME_UP_WRITERS = "deployement_jalon_come_up_writers";

    public const SEARCH = 'search';

    public const DEPLOYEMENT_DEPLOYED_WRITABLE = 'deployement_deployed_writable';
    public const DEPLOYEMENT_DEPLOYED_WRITABLE_TERMINATED = 'deployement_deployed_writable_terminated';
    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $gestionnaire;

    public function __construct(CurrentUser $currentUser)
    {
        $this->user = $currentUser->getUser();
        $this->gestionnaire = Role::isGestionnaire($this->user);
    }


    public function get(string $type, ?string $param = null): DeployementDto
    {
        $dto = new DeployementDto();
        switch ($type) {
            case self::DEPLOYEMENT_WITHOUT_JALON_WRITERS:
                $this->addUser($dto);
                $dtoA=new ActionDto();
                $dtoA->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $dto
                    ->setIsWriter(DeployementDto::TRUE)
                    ->setHasJalon(DeployementDto::FALSE)
                    ->setActionDto($dtoA)
                    ->setVisible(DeployementDto::TRUE);
                break;
            case self::DEPLOYEMENT_JALON_TO_LATE_WRITERS:
                $this->addUser($dto);
                $dtoA = new ActionDto();
                $dtoA->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $dto
                    ->setIsWriter(ActionDto::TRUE)
                    ->setHasJalonToLate(DeployementDto::TRUE)
                    ->setIsTerminated(DeployementDto::FALSE)
                    ->setActionDto($dtoA)
                    ->setVisible(DeployementDto::TRUE);
                break;
            case self::DEPLOYEMENT_JALON_TO_NEAR_WRITERS:
                $this->addUser($dto);
                $dtoA = new ActionDto();
                $dtoA->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $dto
                    ->setIsWriter(ActionDto::TRUE)
                    ->setHasJalonToNear(DeployementDto::TRUE)
                    ->setIsTerminated(DeployementDto::FALSE)
                    ->setActionDto($dtoA)
                    ->setVisible(DeployementDto::TRUE);
                break;
            case self::DEPLOYEMENT_JALON_COME_UP_WRITERS:
                $this->addUser($dto);
                $dtoA = new ActionDto();
                $dtoA->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $dto
                    ->setIsWriter(ActionDto::TRUE)
                    ->setHasJalonComeUp(DeployementDto::TRUE)
                    ->setIsTerminated(DeployementDto::FALSE)
                    ->setActionDto($dtoA)
                    ->setVisible(DeployementDto::TRUE);
                break;                                
            case self::DEPLOYEMENT_WITHOUT_WRITERS:
                $dtoA = new ActionDto();
                $dtoA->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $dto
                    ->setHasWriters(DeployementDto::FALSE)
                    ->setActionDto($dtoA)
                    ->setVisible(DeployementDto::TRUE);
                break;
            case self::DEPLOYEMENT_DEPLOYED_WRITABLE:
                $this->addUser($dto);
                $dtoA = new ActionDto();
                $dtoA->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $dto
                    ->setIsWritable(DeployementDto::TRUE)
                    ->setIsTerminated(DeployementDto::FALSE)
                    ->setActionDto($dtoA)
                    ->setVisible(DeployementDto::TRUE);
                break;
            case self::DEPLOYEMENT_DEPLOYED_WRITABLE_TERMINATED:
                $this->addUser($dto);
                $dtoA = new ActionDto();
                $dtoA->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $dto
                    ->setIsWritable(DeployementDto::TRUE)
                    ->setIsTerminated(DeployementDto::TRUE)
                    ->setActionDto($dtoA)
                    ->setVisible(DeployementDto::TRUE);
                break;                
        }

        return $dto;
    }


    private function addUser(DeployementDto $dto)
    {
        if (!is_null($this->user)) {
            $dto->setUserDto((new UserDto())->setId($this->user->getId()));
        }
        return $dto;
    }
}
