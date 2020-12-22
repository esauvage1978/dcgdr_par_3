<?php

declare(strict_types=1);

namespace App\Dto;

trait TraitJalon
{
    /** @var ?String */
    private $hasJalon;
    
    /** @var ?String */
    private $hasJalonToLate;

    /** @var ?String */
    private $hasJalonToNear;

    /** @var ?String */
    private $hasJalonComeUp;

    /**
     * @return mixed
     */
    public function getHasJalon()
    {
        return $this->hasJalon;
    }

    /**
     * @param mixed $hasJalon
     */
    public function setHasJalon($hasJalon)
    {
        $this->checkBool($hasJalon);
        $this->hasJalon = $hasJalon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasJalonToLate()
    {
        return $this->hasJalonToLate;
    }

    /**
     * @param mixed $hasJalonToLate
     */
    public function setHasJalonToLate($hasJalonToLate)
    {
        $this->checkBool($hasJalonToLate);
        $this->hasJalonToLate = $hasJalonToLate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasJalonToNear()
    {
        return $this->hasJalonToNear;
    }

    /**
     * @param mixed $hasJalonToNear
     */
    public function setHasJalonToNear($hasJalonToNear)
    {
        $this->checkBool($hasJalonToNear);
        $this->hasJalonToNear = $hasJalonToNear;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasJalonComeUp()
    {
        return $this->hasJalonComeUp;
    }

    /**
     * @param mixed $hasJalonComeUp
     */
    public function setHasJalonComeUp($hasJalonComeUp)
    {
        $this->checkBool($hasJalonComeUp);
        $this->hasJalonComeUp = $hasJalonComeUp;
        return $this;
    }
}
