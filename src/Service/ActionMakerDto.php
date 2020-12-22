<?php

namespace App\Service;

use App\Dto\ActionDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\ActionDtoRepository;
use App\Security\CurrentUser;
use App\Security\Role;
use App\Workflow\WorkflowData;

class ActionMakerDto
{
    const HOME_SUBSCRIPTION = 'home_subscription';
    const HOME_ALL = 'home_all';
    const HOME_NEWS_SUBSCRIPTION = 'news_subscription';
    const HOME_NEWS = 'news';

    public const ACTION_WITHOUT_WRITERS = "action_without_writers";
    public const ACTION_WITHOUT_VALIDERS_COTECH = "action_without_validers_cotech";
    public const ACTION_WITHOUT_VALIDERS_CODIR = "action_without_validers_codir";

    public const ACTION_WITHOUT_JALON_WRITERS = "action_without_jalon_writers";
    public const ACTION_WITHOUT_JALON_VALIDERS_COTECH = "action_without_jalon_validers_cotech";
    public const ACTION_WITHOUT_JALON_VALIDERS_CODIR = "action_without_jalon_validers_codir";

    public const ACTION_JALON_TO_LATE_WRITERS = "action_jalon_to_late_writers";
    public const ACTION_JALON_TO_LATE_VALIDERS_COTECH = "action_jalon_to_late_validers_cotech";
    public const ACTION_JALON_TO_LATE_VALIDERS_CODIR = "action_jalon_to_late_validers_codir";

    public const SEARCH = 'search';

    public const STARTED = 'started';
    public const STARTED_WRITABLE = 'started_writable';
    public const STARTED_READABLE = 'started_readable';

    public const ABANDONNED = 'abandonned';
    public const ABANDONNED_WRITABLE = 'abandonned_writable';
    public const ABANDONNED_READABLE = 'abandonned_readable';

    public const COTECH = 'cotech';
    public const COTECH_WRITABLE = 'cotech_writable';
    public const COTECH_READABLE = 'cotech_readable';

    public const CODIR = 'codir';
    public const CODIR_WRITABLE = 'codir_writable';
    public const CODIR_READABLE = 'codir_readable';

    public const REJECTED = 'rejected';
    public const REJECTED_WRITABLE = 'rejected_writable';
    public const REJECTED_READABLE = 'rejected_readable';

    public const FINALISED = 'finalised';
    public const FINALISED_WRITABLE = 'finalised_writable';
    public const FINALISED_READABLE = 'finalised_readable';

    public const DEPLOYED = 'deployed';
    public const DEPLOYED_WRITABLE = 'deployed_writable';
    public const DEPLOYED_READABLE = 'deployed_readable';

    public const MEASURED = 'measured';
    public const MEASURED_WRITABLE = 'measured_writable';
    public const MEASURED_READABLE = 'measured_readable';

    public const CLOTURED = 'clotured';
    public const CLOTURED_WRITABLE = 'clotured_writable';
    public const CLOTURED_READABLE = 'clotured_readable';

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


    public function get(string $type, ?string $param = null): ActionDto
    {
        $dto = new ActionDto();
        switch ($type) {
            case self::ACTION_WITHOUT_JALON_WRITERS:
                $this->addUser($dto);
                $dto
                    ->setIsWriter(ActionDto::TRUE)
                    ->setHasJalon(ActionDto::FALSE)
                    ->setStates([
                        WorkflowData::STATE_STARTED,
                        WorkflowData::STATE_FINALISED,
                        WorkflowData::STATE_MEASURED
                    ])
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ACTION_WITHOUT_JALON_VALIDERS_COTECH:
                $this->addUser($dto);
                $dto
                    ->setIsValidersCOTECH(ActionDto::TRUE)
                    ->setHasJalon(ActionDto::FALSE)
                    ->setStateCurrent(WorkflowData::STATE_COTECH)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ACTION_WITHOUT_JALON_VALIDERS_CODIR:
                $this->addUser($dto);
                $dto
                    ->setIsValidersCODIR(ActionDto::TRUE)
                    ->setHasJalon(ActionDto::FALSE)
                    ->setStateCurrent(WorkflowData::STATE_CODIR)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ACTION_JALON_TO_LATE_WRITERS:
                $this->addUser($dto);
                $dto
                    ->setIsWriter(ActionDto::TRUE)
                    ->setHasJalonToLate(ActionDto::TRUE)
                    ->setStates([
                        WorkflowData::STATE_STARTED,
                        WorkflowData::STATE_FINALISED,
                        WorkflowData::STATE_MEASURED
                    ])
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ACTION_JALON_TO_LATE_VALIDERS_COTECH:
                $this->addUser($dto);
                $dto
                    ->setIsValidersCOTECH(ActionDto::TRUE)
                    ->setHasJalonToLate(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_COTECH)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ACTION_JALON_TO_LATE_VALIDERS_CODIR:
                $this->addUser($dto);
                $dto
                    ->setIsValidersCODIR(ActionDto::TRUE)
                    ->setHasJalonToLate(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_CODIR)
                    ->setVisible(ActionDto::TRUE);
                break;                
            case self::ACTION_WITHOUT_WRITERS:

                $dto
                    ->setHasWriters(ActionDto::FALSE)
                    ->setStates([WorkflowData::STATE_STARTED, WorkflowData::STATE_FINALISED])
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ACTION_WITHOUT_VALIDERS_COTECH:
                $this->addUser($dto);
                $dto
                    ->setIsWriter(ActionDto::TRUE)
                    ->setHasValidersCOTECH(ActionDto::FALSE)
                    ->setStates([WorkflowData::STATE_STARTED, WorkflowData::STATE_FINALISED])
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ACTION_WITHOUT_VALIDERS_CODIR:
                $this->addUser($dto);
                $dto
                    ->setIsWriter(ActionDto::TRUE)
                    ->setHasValidersCODIR(ActionDto::FALSE)
                    ->setStates([WorkflowData::STATE_STARTED, WorkflowData::STATE_FINALISED])
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::STARTED_WRITABLE:
                $this->addUser($dto);
                $dto
                    ->setIsWritable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_STARTED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::STARTED_READABLE:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_STARTED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::STARTED:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_STARTED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::COTECH_WRITABLE:
                $this->addUser($dto);
                $dto
                    ->setIsWritable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_COTECH)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::COTECH_READABLE:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_COTECH)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::COTECH:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_COTECH)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::CODIR_WRITABLE:
                $this->addUser($dto);
                $dto
                    ->setIsWritable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_CODIR)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::CODIR_READABLE:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_CODIR)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::CODIR:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_CODIR)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::REJECTED_WRITABLE:
                $this->addUser($dto);
                $dto
                    ->setIsWritable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_REJECTED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::REJECTED_READABLE:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_REJECTED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::REJECTED:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_REJECTED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::FINALISED_WRITABLE:
                $this->addUser($dto);
                $dto
                    ->setIsWritable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_FINALISED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::FINALISED_READABLE:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_FINALISED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::FINALISED:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_FINALISED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::DEPLOYED_WRITABLE:
                $this->addUser($dto);
                $dto
                    ->setIsWritable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_DEPLOYED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::DEPLOYED_READABLE:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_DEPLOYED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::DEPLOYED:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_DEPLOYED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::MEASURED_WRITABLE:
                $this->addUser($dto);
                $dto
                    ->setIsWritable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_MEASURED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::MEASURED_READABLE:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_MEASURED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::MEASURED:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_MEASURED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::CLOTURED_WRITABLE:
                $this->addUser($dto);
                $dto
                    ->setIsWritable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_CLOTURED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::CLOTURED_READABLE:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_CLOTURED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::CLOTURED:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_CLOTURED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ABANDONNED_WRITABLE:
                $this->addUser($dto);
                $dto
                    ->setIsWritable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_ABANDONNED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ABANDONNED_READABLE:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_ABANDONNED)
                    ->setVisible(ActionDto::TRUE);
                break;
            case self::ABANDONNED:
                $this->addUser($dto);
                $dto
                    ->setIsReadable(ActionDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_ABANDONNED)
                    ->setVisible(ActionDto::TRUE);
                break;
        }

        return $dto;
    }


    private function addUser(ActionDto $dto)
    {
        if (!is_null($this->user)) {
            $dto->setUserDto((new UserDto())->setId($this->user->getId()));
        }
        return $dto;
    }
}
