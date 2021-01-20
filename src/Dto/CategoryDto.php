<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class CategoryDto extends AbstractDto
{

    private $ref;
    /**
     * @var ThematiqueDto;
     */
    private $thematiqueDto;

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
