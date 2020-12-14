<?php

namespace App\Dto;

use App\Workflow\WorkflowData;
use Symfony\Component\HttpFoundation\Request;

class ActionDto extends AbstractDto
{

    /**
     * @var ?UserDto
     */
    private $userDto;

    /**
     * @var ?string
     */
    private $stateCurrent;

    /**
     * @var ?string
     */
    private $isInProgress;


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
    public function getStateCurrent()
    {
        return $this->stateCurrent;
    }

    /**
     * @param mixed $stateCurrent
     * @return ActionDto
     */
    public function setStateCurrent($stateCurrent)
    {
        $this->stateCurrent = $stateCurrent;
        return $this;
    }

    /**
     * @param mixed $IsInProgress
     * @return ActionDto
     */
    public function setIsInProgress($isInProgress)
    {
        $this->checkBool($isInProgress);
        $this->isInProgress = $isInProgress;
        return $this;
    }

    /**
     * @param mixed $isShow
     * @return ActionDto
     */
    public function setIsShow($isShow)
    {
        $this->checkBool($isShow);
        $this->isShow = $isShow;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getIsShow()
    {
        return $this->isShow;
    }


    public function getData(): array
    {
        $d = [];
        isset($this->wordSearch) && $d = array_merge($d, ['wordSearch' => $this->wordSearch]);
        isset($this->visible) && $d = array_merge($d, ['visible' => $this->visible]);
        isset($this->visible) && $d = array_merge($d, ['stateCurrent' => $this->stateCurrent]);
        isset($this->userDto) && isset($this->userDto->id) && $d = array_merge($d, ['user' => $this->userDto->id]);
        return $d;
    }
    public function setData(Request $datas)
    {
        null !== $datas->get('wordSearch') && $this->wordSearch = $datas->get('wordSearch');
        null !== $datas->get('visible') && $this->visible = $datas->get('visible');
        null !== $datas->get('user') && $this->userDto = (new UserDto())->setId($datas->get('user'));
        null !== $datas->get('stateCurrent') && $this->stateCurrent = $datas->get('stateCurrent');
    }

}
