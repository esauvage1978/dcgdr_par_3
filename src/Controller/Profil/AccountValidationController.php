<?php

namespace App\Controller\Profil;

use App\Security\Role;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Controller\AbstractGController;
use App\Service\SendMailValidationAccount;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class AccountValidationController extends AbstractGController
{

    /**
    * @Route("/sendmail/accountvalidated", methods={"GET"}, name="profil_sendmail_account_validated")
    */
    public function sendmailAccountActivationAction(SendMailValidationAccount $sendMailValidationAccount): Response
    {
        $sendMailValidationAccount->send($this->getUser());
        $this->addFlash('success', 'Le mail est envoyé aux administrateurs.');

        return $this->redirectToRoute('home');
    }

        /**
     * @Route("/valideAccount/{token}", name="user_valide_account", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function validate(string $token, UserManager $userManager, UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['accountValidatedToken' => $token]);
        $role = [Role::ROLE_USER];
        if (Role::hasGestionnaire($user)) {
            array_push($role, Role::ROLE_GESTIONNAIRE);
        }
        if (Role::isAdmin($user)) {
            array_push($role, Role::ROLE_ADMIN);
        }

        $user
            ->setAccountValidated(true)
            ->setRoles($role)
            ->setAccountValidatedToken(date_format(new \DateTime(), 'Y-m-d H:i:s'));
        $userManager->save($user);
        return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
    }
}
