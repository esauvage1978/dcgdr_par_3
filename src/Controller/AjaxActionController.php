<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Category;
use App\Service\BackpackRefGenerator;
use App\Repository\BackpackRepository;
use App\Repository\ProcessDtoRepository;
use App\Repository\MProcessDtoRepository;
use App\Service\BackpackRefControllator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxActionController extends AbstractGController
{
    /**
     * @Route("/ajax/actionsforcategory/{id}", name="ajax_actionforcategory", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxActionForCategory(Request $request,  Category $category): Response
    {
        return $this->json([
            'code' => 200,
            'value' => $this->renderView('category/_show/_actionsList.html.twig', ['item' => $category]),
            'message' => 'données transmises'
        ], 200);
    }
}
