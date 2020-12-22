<?php

namespace App\Service;


use App\Repository\DeployementDtoRepository;
use App\Security\CurrentUser;

class DeployementCounter
{

    /**
     * @var DeployementDtoRepository
     */
    private $deployementDtoRepository;

    private $deployementMakerDto;

    public function __construct(
        DeployementDtoRepository $deployementDtoRepository,
        CurrentUser $currentUser
    )
    {
        $this->deployementDtoRepository = $deployementDtoRepository;
        $this->deployementMakerDto=new DeployementMakerDto($currentUser);
    }

    public function get(string $type,?string $param=null)
    {
        return $this->deployementDtoRepository->countForDto(
            $this->deployementMakerDto->get($type,$param)
        );
    }

}
