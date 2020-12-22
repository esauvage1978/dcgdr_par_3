<?php

namespace App\Service;


use App\Repository\ActionDtoRepository;
use App\Security\CurrentUser;

class ActionCounter
{

    /**
     * @var ActionDtoRepository
     */
    private $actionDtoRepository;

    private $actionMakerDto;

    public function __construct(
        ActionDtoRepository $actionDtoRepository,
        CurrentUser $currentUser
    )
    {
        $this->actionDtoRepository = $actionDtoRepository;
        $this->actionMakerDto=new ActionMakerDto($currentUser);
    }

    public function get(string $type,?string $param=null)
    {
        return $this->actionDtoRepository->countForDto(
            $this->actionMakerDto->get($type,$param)
        );
    }

}
