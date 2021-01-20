<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ActionMakerDto;
use App\Service\DeployementMakerDto;
use App\Repository\ActionDtoRepository;
use App\Repository\DeployementDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function search(
        Request $request,
        ActionDtoRepository $actionDtoRepository,
        ActionMakerDto $actionMakerDto,
        DeployementDtoRepository $deployementDtoRepository,
        DeployementMakerDto $deployementMakerDto
        ) {
        $r = $request->get('r');
        if ($r === null) {
            return $this->redirectToRoute('home');
        } else {

            return $this->render(
                'home/search.html.twig',
                [
                    'actions'
                    =>
                    $actionDtoRepository->findAllForDto($actionMakerDto->get(ActionMakerDto::SEARCH,$r), ActionDtoRepository::FILTRE_DTO_INIT_SEARCH),
'deployements'
                    =>
                    $deployementDtoRepository->findAllForDto($deployementMakerDto->get(DeployementMakerDto::SEARCH, $r), DeployementDtoRepository::FILTRE_DTO_INIT_SEARCH),
                ]
            );
        }
    }
}
