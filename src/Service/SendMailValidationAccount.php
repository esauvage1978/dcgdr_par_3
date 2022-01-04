<?php

namespace App\Service;

use App\Entity\User;
use App\Mail\AdminMail;
use Psr\Log\LoggerInterface;
use App\Repository\UserRepository;


class SendMailValidationAccount
{

    /**
     * @var AdminMail
     */
    private $mail;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var LoggerInterface
     */
    private $logger; 

    public function __construct(AdminMail $mail,UserRepository $userRepository,LoggerInterface $logger)
    {
        $this->mail = $mail;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }


    public function send(User $user)
    {
        $admins=$this->userRepository->findAllForContactAdmin();
        foreach($admins as $admin) {
            $this->logger->info('Mail pour validation du compte de ' . $user->getUsername() . ' à ' . $admin->getUsername());
            $this->mail->send($admin, $user, AdminMail::VALIDATE, 'Validation d\'un nouveau compte');
        }
    }

}
