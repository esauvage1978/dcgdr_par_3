<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MakeDashboard;
use App\Service\BackpackCounter;
use App\Repository\BackpackDtoRepository;
use App\Service\BackpackMakerDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @IsGranted("ROLE_USER")
     */
    public function index(BackpackDtoRepository $backpackDtoRepository)
    {
        $md = new MakeDashboard($backpackDtoRepository, $this->getUser());

        $draft = [
            $md->getData(ActionMakerDto::DRAFT),
        ];


        return $this->render(
            'dashboard/index.html.twig',
            [
                'draft' => $draft,
            ]
        );
    }
}