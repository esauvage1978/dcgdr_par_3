<?php

declare(strict_types=1);

namespace App\Mail;

use App\Entity\User;

use function in_array;
use App\Entity\Action;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Classe permettant l'envoi d'un mail en rapport avec les utilisateurs
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 *
 */
class ActionMail
{
    public const TOCOTECH = 'workflow/toCotech';
    public const TOCODIR = 'workflow/toCodir';
    public const TOCONTROL = 'workflow/toControl';
    public const TOCHECK = 'workflow/toCheck';
    public const PUBLISHED = 'workflow/published';
    public const TOREVISE = 'workflow/toRevise';
    public const INREVIEW = 'workflow/inReview';

    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function send(User $user, Action $action, string $context, string $subject): int
    {
        $context = 'workflow/' . $context;

        $contextValid = [
            self::TOCOTECH,
            self::TOCODIR,
        ];

        if (!in_array($context, $contextValid)) {
            throw new \exception('Le context n\est pas présente dans la liste UserMail : ' . $context);
        }

        $this->mail
            ->setUserTo($user)
            ->setContext($context)
            ->setSubject($subject)
            ->setParamsTwig(['user' => $user, 'action' => $action]);

        return $this->mail->send();
    }

    public function sendForUsers(ArrayCollection $users, Action $action, string $context, string $subject)
    {
        foreach ($users as $user) {
            $this->send($user, $action, $context, $subject);
        }
    }
}
