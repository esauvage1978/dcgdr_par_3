<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class DeployementDto extends AbstractDto
{

    use TraitJalon;
    use TraitWriter;

    /**
     * @var ?string
     */
    private $isTerminated;

    /**
     * @var ActionDto;
     */
    private $actionDto;

    /**
     * @var ?UserDto
     */
    private $userDto;

    /**
     * @return mixed
     */
    public function getUserDto()
    {
        return $this->userDto;
    }

    /**
     * @param mixed $userDto
     */
    public function setUserDto($userDto)
    {
        $this->userDto = $userDto;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getIsTerminated()
    {
        return $this->isTerminated;
    }

    /**
     * @param mixed $isTerminated
     */
    public function setIsTerminated($isTerminated)
    {
        $this->checkBool($isTerminated);
        $this->isTerminated = $isTerminated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionDto()
    {
        return $this->actionDto;
    }

    /**
     * @param mixed $axeDto
     */
    public function setActionDto($actionDto)
    {
        $this->actionDto = $actionDto;
        return $this;
    }

    public function getData(): array
    {
        $d = [];
        isset($this->wordSearch) && $d = array_merge($d, ['wordSearch' => $this->wordSearch]);
        isset($this->visible) && $d = array_merge($d, ['visible' => $this->visible]);
        return $d;
    }
    
    public function setData(Request $datas)
    {
        null !== $datas->get('wordSearch') && $this->wordSearch = $datas->get('wordSearch');
        null !== $datas->get('visible') && $this->visible = $datas->get('visible');
    }
}
