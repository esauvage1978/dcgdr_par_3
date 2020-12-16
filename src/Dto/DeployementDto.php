<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class DeployementDto extends AbstractDto
{
    /**
     * @var ActionDto;
     */
    private $actionDto;

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
