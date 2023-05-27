<?php

namespace App\Service;

use App\Entity\Application\Application;
use App\Repository\ApplicationRecipientRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private ApplicationRecipientRepository $recipientRepo,
    ) {
    }

    public function sendApplicationEmail(Application $application): void
    {
        $recipients = $this->recipientRepo->findActive();

        $toAddresses = array_map(
            fn($recipient) => new Address($recipient->getEmail(), $recipient->getName()),
            $recipients
        );

        if (empty($toAddresses)) {
            // Don't bother sending an email to nobody
            return;
        }

        $email = (new TemplatedEmail())
            ->from(new Address('apply@photowalkboston.com', 'Photo Walk Boston Applications'))
            ->to(...$toAddresses)
            ->subject(sprintf("%s's request to join Photo Walk Boston", $application->getName()))
            ->replyTo(new Address($application->getEmail(), $application->getName()))
            ->htmlTemplate('email/application.html.twig')
            ->context(['application' => $application]);

        $this->mailer->send($email);
    }
}
