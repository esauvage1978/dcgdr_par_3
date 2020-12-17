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
     * @var ?AxeDto
     */
    private $axeDto;

    /**
     * @var ?PoleDto
     */
    private $poleDto;
    /**
     * @var ?ThematiqueDto
     */
    private $thematiqueDto;
    /**
     * @var ?CategoryDto
     */
    private $categoryDto;
    /**
     * @var ?string
     */
    private $stateCurrent;

    /**
     * @var ?string
     */
    private $isWritable;

    /**
     * @var ?string
     */
    private $isReadable;

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
    public function getAxeDto()
    {
        return $this->axeDto;
    }

    /**
     * @param mixed $axeDto
     */
    public function setAxeDto($axeDto)
    {
        $this->axeDto = $axeDto;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getPoleDto()
    {
        return $this->poleDto;
    }

    /**
     * @param mixed $poleDto
     */
    public function setPoleDto($poleDto)
    {
        $this->poleDto = $poleDto;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getThematiqueDto()
    {
        return $this->thematiqueDto;
    }

    /**
     * @param mixed $thematiqueDto
     */
    public function setThematiqueDto($thematiqueDto)
    {
        $this->thematiqueDto = $thematiqueDto;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getCategoryDto()
    {
        return $this->categoryDto;
    }

    /**
     * @param mixed $categoryDto
     */
    public function setCategoryDto($categoryDto)
    {
        $this->categoryDto = $categoryDto;
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
     * @param mixed $isWritable
     * @return ActionDto
     */
    public function setIsWritable($isWritable)
    {
        $this->checkBool($isWritable);
        $this->isWritable = $isWritable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsWritable()
    {
        return $this->isWritable;
    }

    /**
     * @param mixed $isReadable
     * @return ActionDto
     */
    public function setIsReadable($isReadable)
    {
        $this->checkBool($isReadable);
        $this->isReadable = $isReadable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsReadable()
    {
        return $this->isReadable;
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
