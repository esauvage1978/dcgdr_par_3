<?php

namespace App\Dto;

use App\Workflow\WorkflowData;
use Symfony\Component\HttpFoundation\Request;

class ActionDto extends AbstractDto
{
    Use TraitJalon;
    use TraitWriter;

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
    private $states;

    private $searchDate;
    private $search;

    private $ref;

    /**
     * @var ?string
     */

    private $isValidersCOTECH;
    private $isValidersCODIR;
    /**
     * @var ?string
     */
    private $isReadable;


    private $hasValidersCOTECH;
    private $hasValidersCODIR;

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param mixed $search
     * @return ActionSearchDto
     */
    public function setSearch($search)
    {
        $this->search = $search;

        $this->searchReference();

        $this->searchDate();

        return $this;
    }

    private function searchReference()
    {
        if (!empty($this->search)) {
            
            if (mb_substr_count($this->search, '-') == 2) {
                
                $temp = explode('-', $this->search);
                $thematiqueDto=new ThematiqueDto();
                $thematiqueDto->setRef($temp[0]);
                $this->setThematiqueDto($thematiqueDto);
                $categoryDto = new CategoryDto();
                $categoryDto->setRef($temp[1]);
                $this->setCategoryDto($categoryDto);
                $this->setRef($temp[2]);
                $this->search = null;
            }
        }
    }

    private function searchDate()
    {
        if (!empty($this->search)) {
            $d = $this->validateDate($this->search);
            if (!empty($d)) {
                $this->setSearchDate($d);
                $this->search = null;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getSearchDate()
    {
        return $this->searchDate;
    }

    /**
     * @param mixed $searchDate
     */
    public function setSearchDate($searchDate)
    {
        $this->searchDate = $searchDate;
        return $this;
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

    function validateDate($date)
    {
        if (mb_substr_count($this->search, '/') == 2) {
            $d = explode('/', $date);
            return (strlen($d[2]) == 2 ? '20' . $d[2] : $d[2])
                . '-' .
                (strlen($d[1]) == 2 ? $d[1] : '0' . $d[1])
                . '-' .
                (strlen($d[0]) == 2 ? $d[0] : '0' . $d[0]);
        }
        return null;
    }

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
     * @return mixed
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @param mixed $states
     * @return ActionDto
     */
    public function setStates($states)
    {
        $this->states = $states;
        return $this;
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
    public function getHasValidersCOTECH()
    {
        return $this->hasValidersCOTECH;
    }

    /**
     * @param mixed $hasValidersCOTECH
     * @return ActionDto
     */
    public function setHasValidersCOTECH($hasValidersCOTECH)
    {
        $this->checkBool($hasValidersCOTECH);
        $this->hasValidersCOTECH = $hasValidersCOTECH;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasValidersCODIR()
    {
        return $this->hasValidersCODIR;
    }

    /**
     * @param mixed $hasValidersCODIR
     * @return ActionDto
     */
    public function setHasValidersCODIR($hasValidersCODIR)
    {
        $this->checkBool($hasValidersCODIR);
        $this->hasValidersCODIR = $hasValidersCODIR;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsValidersCOTECH()
    {
        return $this->isValidersCOTECH;
    }

    /**
     * @param mixed $isValidersCOTECH
     * @return ActionDto
     */
    public function setIsValidersCOTECH($isValidersCOTECH)
    {
        $this->checkBool($isValidersCOTECH);
        $this->isValidersCOTECH = $isValidersCOTECH;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsValidersCODIR()
    {
        return $this->isValidersCODIR;
    }

    /**
     * @param mixed $isValidersCODIR
     * @return ActionDto
     */
    public function setIsValidersCODIR($isValidersCODIR)
    {
        $this->checkBool($isValidersCODIR);
        $this->isValidersCODIR = $isValidersCODIR;
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
