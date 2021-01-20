<?php

declare(strict_types=1);

namespace App\Dto;

interface DtoInterface
{
    public function getPage(): ?string;

    public function setPage($page);
}
