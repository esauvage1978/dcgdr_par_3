<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\AxeDto;
use App\Repository\AxeDtoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AxeDtoRepository $axeDtoRepository)
    {
        $dto = new AxeDto();
        $dto->setVisible(AxeDto::TRUE);


        return $this->render('home/index.html.twig', [
            'axes' => $axeDtoRepository->findAllForDto($dto,AxeDtoRepository::FILTRE_DTO_INIT_HOME)
        ]);
    }

}
