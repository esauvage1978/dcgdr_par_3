<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Mailer;
use App\Mail\MailerMail;
use App\Entity\Deployement;
use App\Manager\MailerManager;
use App\Form\Mailer\MailerFormActionType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Mailer\MailerFormDeployementType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer/composer/{id}", name="mailer_action_composer")
     */
    public function mailerActionComposer(
        Request $request,
        Action $action,
        MailerManager $manager,
        MailerMail $mailerMail
    ) {
        $form = $this->createForm(MailerFormActionType::class, ['data' => $action->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mailer = $manager->initialiseMailer($form->getData());
            $users= $manager->getUsersEmailTo();

            if (is_a($mailer, Mailer::class) && $users->count()>0) {
                
                $mailer->setAction($action);
                
                foreach($users as $user){
                    $mailerMail->send($user,$mailer,MailerMail::ACTION,$mailer->getSubject());
                }

                $manager->save($mailer);

                $this->addFlash(AbstractGController::SUCCESS, 'Message envoyé');
            } else {
                $this->addFlash(AbstractGController::DANGER, 'Le mail n\'est pas envoyé. La cause probable est une absence de destinataire');
            }
        }
        return $this->render('mailer/composerAction.html.twig', [
            'item' => $action,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mailer/{id}", name="mailer_action_history")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailierActionHistory(
        Action $action
    ) {
        return $this->render('mailer/history_action.html.twig', [
            'item' => $action,
        ]);
    }

    /**
     * @Route("/mailer/composer/deployement/{id}", name="mailer_deployement_composer")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailerDeployementComposer(
        Request $request,
        Deployement $deployement,
        MailerManager $manager,
        MailerMail $mailerMail
    ) {
        $form = $this->createForm(MailerFormDeployementType::class, ['data' => $deployement->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mailer = $manager->initialiseMailer($form->getData());
            $users = $manager->getUsersEmailTo();

            if (is_a($mailer, Mailer::class) && $users->count() > 0) {

                $mailer->setDeployement($deployement);

                foreach ($users as $user) {
                    $mailerMail->send($user, $mailer, MailerMail::DEPLOYEMENT, $mailer->getSubject());
                }

                $manager->save($mailer);

                $this->addFlash(AbstractGController::SUCCESS, 'Message envoyé');
            } else {
                $this->addFlash(AbstractGController::DANGER, 'Le mail n\'est pas envoyé. La cause probable est une absence de destinataire');
            }
        }
        return $this->render('mailer/composerDeployement.html.twig', [
            'item' => $deployement,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mailer/deployement/{id}", name="mailer_deployement_history")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailierDeployementHistory(
        Deployement $deployement
    ) {
        return $this->render('mailer/history_deployement.html.twig', [
            'item' => $deployement,
        ]);
    }
}
