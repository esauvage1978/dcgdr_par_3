<?php

namespace App\Workflow\Transaction;

use App\Entity\Action;
use App\Repository\ActionRepository;

interface Transition
{
    public function __construct(Action $item, ActionRepository $actionRepository);
    public function can();
    public function getExplains(): array;
    public function getCheckMessages(): array;
    public function check();
    public function intialiseActionForTransition(bool $automate=false);
}
