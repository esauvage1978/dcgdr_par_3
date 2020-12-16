<?php

namespace App\Controller;

use App\Controller\AbstractGController;
use App\Entity\Action;
use App\Repository\ActionStateRepository;
use App\Workflow\WorkflowActionManager;
use App\Workflow\WorkflowData;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/workflow")
 */
class WorkflowController extends AbstractGController
{

    /**
     * @Route("/{id}/check", name="workflow_action_check", methods={"GET","POST"})
     *
     * @param Action          $action
     * @param WorkflowActionManager $workflow
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function checkAction(Action $action, WorkflowActionManager $workflow): Response
    {
        return $this->render('verif/workflow.html.twig', [
            'item' => $action,
        ]);
    }

    /**
     * @Route("/{id}/check/{transition}", name="workflow_action_check_apply_transition", methods={"GET","POST"})
     *
     * @param Action          $action
     * @param WorkflowActionManager $workflow
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function checkApplyTransition(Action $action, WorkflowActionManager $workflow, string $transition): Response
    {
        $action->setStateContent('Modification avec la transition : ' . $transition);

        $workflow->applyTransition($action, $transition, 'Modification effectuée par l\'administrateur');

        return $this->redirectToRoute('workflow_action_check', ['id' => $action->getId()]);
    }


    /**
     * @Route("/{id}/history", name="workflow_action_history", methods={"GET"})
     *
     * @param ActionStateRepository $repository
     * @param Action $action
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function showHistoryAction(Action $action): Response
    {
        return $this->render('action/workflowHistory.html.twig', [
            'item' => $action
        ]);
    }


    /**
     * @Route("/{id}/notification", name="workflow_action_notification", methods={"GET"})
     *
     * @param ActionStateRepository $repository
     * @param Action $action
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function showNotificationAction(Action $action): Response
    {
        return $this->render('action/workflowNotification.html.twig', [
            'item' => $action
        ]);
    }

    /**
     * @Route("/{id}/{transition}", name="workflow_action_apply_transition", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Action $item
     * @param WorkflowActionManager $workflowActionManager
     * @param string $transition
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function applyTransitionAction(Request $request, Action $item, WorkflowActionManager $workflowActionManager, string $transition): Response
    {
        if (WorkflowData::hasTransition($transition)===false) {
            throw new Exception('transition non présente : ' . $transition);
        }

        if ($this->isCsrfTokenValid($transition . $item->getId(), $request->request->get('_token'))) {

            $content = $request->request->get($transition . '_content');

            $result = $workflowActionManager->applyTransition($item, $transition, $content);

            if ($result) {
                $this->addFlash(self::SUCCESS, 'Le changement d\'état est effectué');

                return $this->redirectToRoute('action_show', ['id' => $item->getId()]);
            }
            $this->addFlash(self::DANGER, 'Le changement d\'état n\'a pas abouti. Les conditions ne sont pas remplies.');
        }

        return $this->redirectToRoute('action_show', ['id' => $item->getId()]);
    }


}
