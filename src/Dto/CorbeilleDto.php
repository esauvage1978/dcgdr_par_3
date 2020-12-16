<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class CorbeilleDto extends AbstractDto
{
    /**
     * @var OrganismeDto;
     */
    private $organismeDto;

    /** @var ?string */
    protected $isUseByDefault;

    /**
     * @return mixed
     */
    public function getOrganismeDto()
    {
        return $this->organismeDto;
    }

    /**
     * @param mixed $axeDto
     */
    public function setOrganismeDto($organismeDto)
    {
        $this->organismeDto = $organismeDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsUseByDefault()
    {
        return $this->isUseByDefault;
    }

    /**
     * @param mixed $isUseByDefault
     */
    public function setIsUseByDefault($isUseByDefault): AbstractDto
    {
        $this->checkBool($isUseByDefault);
        $this->isUseByDefault = $isUseByDefault;

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
