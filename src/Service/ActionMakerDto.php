<?php

namespace App\Service;

use App\Dto\ActionDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\ActionDtoRepository;
use App\Security\Role;
use App\Workflow\WorkflowData;

class ActionMakerDto
{
    const HOME_SUBSCRIPTION = 'home_subscription';
    const HOME_ALL = 'home_all';
    const HOME_NEWS_SUBSCRIPTION = 'news_subscription';
    const HOME_NEWS = 'news';

    public const BACKPACK_IN_PROGRESS = 'backpack_in_progress';

    public const SEARCH = 'search';

    public const STARTED = 'started';
    public const MY_DRAFT_UPDATABLE = 'mydraft_updatable';
    public const DRAFT_UPDATABLE = 'draft_updatable';

    public const ABANDONNED = 'abandonned';
    public const ABANDONNED_UPDATABLE = 'abandonned_updatable';
    public const MY_ABANDONNED_UPDATABLE = 'myabandonned_updatable';

    public const TO_RESUME = 'toResume';
    public const TO_RESUME_UPDATABLE = 'toResume_updatable';
    public const MY_TO_RESUME_UPDATABLE = 'mytoResume_updatable';

    public const TO_VALIDATE = 'toValidate';
    public const TO_VALIDATE_UPDATABLE = 'toValidate_updatable';
    public const MY_TO_VALIDATE_UPDATABLE = 'mytoValidate_updatable';

    public const PUBLISHED = 'published';
    public const PUBLISHED_UPDATABLE = 'published_updatable';
    public const MY_PUBLISHED_UPDATABLE = 'mypublished_updatable';

    public const GO_TO_REVISE = 'goToRevise';
    public const GO_TO_REVISE_SOON = 'goToReviseSoon';

    public const TO_REVISE = 'toRevise';
    public const TO_REVISE_UPDATABLE = 'toRevise_updatable';
    public const MY_TO_REVISE_UPDATABLE = 'mytoRevise_updatable';

    public const IN_REVIEW = 'inReview';
    public const IN_REVIEW_UPDATABLE = 'inReview_updatable';
    public const MY_IN_REVIEW_UPDATABLE = 'myinReview_updatable';

    public const TO_CONTROL = 'toControl';

    public const TO_CHECK = 'toCheck';

    public const BACKPACK_SHOW = 'show';

    const HIDE = 'hide';

    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $gestionnaire;

    public function __construct(?User $user)
    {
        $this->user = $user;
        $this->gestionnaire = Role::isGestionnaire($this->user);
    }


    public function get(string $type, ?string $param = null): ActionDto
    {
        $dto = new ActionDto();
        $dto = $this->checkUser($dto);
        switch ($type) {
            case self::STARTED:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_STARTED)
                    ->setVisible(ActionDto::TRUE);
                break;
        }

        return $dto;
    }


    private function checkUser(ActionDto $dto)
    {
        if (!is_null($this->user) && !$this->gestionnaire) {
            $dto->setUserDto((new UserDto())->setId($this->user->getId()));
        }
        return $dto;
    }
}
