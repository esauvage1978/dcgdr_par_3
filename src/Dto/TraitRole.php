<?php

declare(strict_types=1);

namespace App\Dto;

trait TraitRole
{
    /** @var ?String */
    private $isGestionnaire;
    
    /** @var ?String */
    private $isGestionnaireLocal;


    /**
     * @return mixed
     */
    public function getIsGestionnaire()
    {
        return $this->isGestionnaire;
    }

    /**
     * @param mixed $isGestionnaire
     */
    public function setIsGestionnaire($isGestionnaire)
    {
        $this->checkBool($isGestionnaire);
        $this->isGestionnaire = $isGestionnaire;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsGestionnaireLocal()
    {
        return $this->isGestionnaireLocal;
    }

    /**
     * @param mixed $isGestionnaireLocal
     */
    public function setIsGestionnaireLocal($isGestionnaireLocal)
    {
        $this->checkBool($isGestionnaireLocal);
        $this->isGestionnaireLocal = $isGestionnaireLocal;
        return $this;
    }

}
