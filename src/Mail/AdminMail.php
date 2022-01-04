<?php

namespace App\Mail;

use App\Entity\User;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class AdminMail
{
    const VALIDATE = 'user/validate_n2';

    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail=$mail;
    }

    public function send(User $userAdmin, User $user,string $context,string $subject): int
    {
        if (!in_array($context, [ self::VALIDATE])) {
            return -1;
        }

        $this->mail
            ->setUserTo($userAdmin)
            ->setContext($context)
            ->setSubject($subject)
            ->setParamsTwig(['user'=>$userAdmin, 'userToValidate'=>$user])
        ;

        return $this->mail->send();
    }

}
