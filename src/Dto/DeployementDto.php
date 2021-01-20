<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class DeployementDto extends AbstractDto
{

    use TraitJalon;
    use TraitWriter;
    use TraitRole;

    private $search;

    private $searchDate;

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

        $this->searchDate();

        return $this;
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
