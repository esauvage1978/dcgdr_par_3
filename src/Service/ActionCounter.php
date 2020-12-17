<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\ActionDtoRepository;
use App\Security\CurrentUser;

class ActionCounter
{

    /**
     * @var ActionDtoRepository
     */
    private $backpackDtoRepository;

    private $backpackMakerDto;

    public function __construct(
        ActionDtoRepository $backpackDtoRepository,
        CurrentUser $currentUser
    )
    {
        $this->backpackDtoRepository = $backpackDtoRepository;
        $this->backpackMakerDto=new ActionMakerDto($currentUser);
    }

    public function get(string $type,?string $param=null)
    {
        return $this->backpackDtoRepository->countForDto(
            $this->backpackMakerDto->get($type,$param)
        );
    }

}