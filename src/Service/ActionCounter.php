<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\ActionDtoRepository;


class ActionCounter
{

    /**
     * @var ActionDtoRepository
     */
    private $backpackDtoRepository;

    private $backpackMakerDto;

    public function __construct(
        ActionDtoRepository $backpackDtoRepository,
        User $user
    )
    {
        $this->backpackDtoRepository = $backpackDtoRepository;
        $this->backpackMakerDto=new ActionMakerDto($user);
    }

    public function get(string $type,?string $param=null)
    {
        return $this->backpackDtoRepository->countForDto(
            $this->backpackMakerDto->get($type,$param)
        );
    }

}
