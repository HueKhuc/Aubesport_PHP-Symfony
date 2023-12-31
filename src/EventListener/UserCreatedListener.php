<?php

declare(strict_types=1);

namespace App\EventListener;

use Twig\Environment;
use App\Event\UserCreatedEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: UserCreatedEvent::NAME)]
class UserCreatedListener
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private Environment $twig
    ) {
    }

    public function __invoke(UserCreatedEvent $userCreatedEvent): void
    {
        $user = $userCreatedEvent->getUser();

        $email = (new Email())
            ->from('noreply@aubesport.fr')
            ->to($user->getEmail())
            ->cc('admin@aubesport.fr')
            ->subject("Confirmation d'inscription AUBesport")
            ->html($this->twig->render('new.user.confirmation.mail.twig', [
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName()
            ]));

        $this->mailer->send($email);
    }
}
