<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MakeActionDashboard;
use App\Service\ActionMakerDto;
use App\Repository\ActionDtoRepository;
use App\Security\CurrentUser;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @IsGranted("ROLE_USER")
     */
    public function index(ActionDtoRepository $actionDtoRepository,CurrentUser $currentUser)
    {
        $md = new MakeActionDashboard($actionDtoRepository, $currentUser);

        $started = [
            $md->getData(ActionMakerDto::STARTED),
            $md->getData(ActionMakerDto::STARTED_READABLE),
            $md->getData(ActionMakerDto::STARTED_WRITABLE),
        ];
        $cotech = [
            $md->getData(ActionMakerDto::COTECH),
            $md->getData(ActionMakerDto::COTECH_READABLE),
            $md->getData(ActionMakerDto::COTECH_WRITABLE),       ];
        $codir = [
            $md->getData(ActionMakerDto::CODIR),
            $md->getData(ActionMakerDto::CODIR_READABLE),
            $md->getData(ActionMakerDto::CODIR_WRITABLE),
        ];
        $rejected = [
            $md->getData(ActionMakerDto::REJECTED),
            $md->getData(ActionMakerDto::REJECTED_READABLE),
            $md->getData(ActionMakerDto::REJECTED_WRITABLE),
        ];
        $finalised = [
            $md->getData(ActionMakerDto::FINALISED),
            $md->getData(ActionMakerDto::FINALISED_READABLE),
            $md->getData(ActionMakerDto::FINALISED_WRITABLE),
        ];
        $deployed = [
            $md->getData(ActionMakerDto::DEPLOYED),
            $md->getData(ActionMakerDto::DEPLOYED_READABLE),
            $md->getData(ActionMakerDto::DEPLOYED_WRITABLE),
        ];
        $measured = [
            $md->getData(ActionMakerDto::MEASURED),
            $md->getData(ActionMakerDto::MEASURED_READABLE),
            $md->getData(ActionMakerDto::MEASURED_WRITABLE),
        ];
        $clotured = [
            $md->getData(ActionMakerDto::CLOTURED),
            $md->getData(ActionMakerDto::CLOTURED_READABLE),
            $md->getData(ActionMakerDto::CLOTURED_WRITABLE),
        ];
        $abandonned = [
            $md->getData(ActionMakerDto::ABANDONNED),
            $md->getData(ActionMakerDto::ABANDONNED_READABLE),
            $md->getData(ActionMakerDto::ABANDONNED_WRITABLE),
        ];


        return $this->render(
            'dashboard/index.html.twig',
            [
                'started' => $started,
                'cotech' => $cotech,
                'codir' => $codir,
                'rejected' => $rejected,
                'finalised' => $finalised,
                'deployed' => $deployed,
                'measured' => $measured,
                'clotured' => $clotured,
                'abandonned' => $abandonned,
            ]
        );
    }
}
