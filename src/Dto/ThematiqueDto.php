<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class ThematiqueDto extends AbstractDto
{

    private $ref;

    /**
     * @var PoleDto;
     */
    private $poleDto;

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

    /**
     * @return mixed
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @param mixed $ref
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
        return $this;
    }
}
