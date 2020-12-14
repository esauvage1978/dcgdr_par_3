<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MakeActionDashboard;
use App\Service\ActionMakerDto;
use App\Repository\ActionDtoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @IsGranted("ROLE_USER")
     */
    public function index(ActionDtoRepository $actionDtoRepository)
    {
        $md = new MakeActionDashboard($actionDtoRepository, $this->getUser());

        $started = [
            $md->getData(ActionMakerDto::STARTED),
        ];


        return $this->render(
            'dashboard/index.html.twig',
            [
                'started' => $started,
            ]
        );
    }
}
