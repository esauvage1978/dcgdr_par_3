<?php

declare(strict_types=1);

namespace App\Dto;

trait TraitWriter
{
    /** @var ?string  */
    private $isWritable;

    /** @var ?string  */
    private $isWriter;

    /** @var ?string  */
    private $hasWriters;


    /**
     * @param mixed $isWritable
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
     * @param mixed $isWriter
     */
    public function setIsWriter($isWriter)
    {
        $this->checkBool($isWriter);
        $this->isWriter = $isWriter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsWriter()
    {
        return $this->isWriter;
    }


    /**
     * @return mixed
     */
    public function getHasWriters()
    {
        return $this->hasWriters;
    }

    /**
     * @param mixed $hasWriters
     */
    public function setHasWriters($hasWriters)
    {
        $this->checkBool($hasWriters);
        $this->hasWriters = $hasWriters;
        return $this;
    }
}
