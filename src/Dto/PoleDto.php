<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class PoleDto extends AbstractDto
{

    /**
     * @var AxeDto;
     */
    private $axeDto;

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
