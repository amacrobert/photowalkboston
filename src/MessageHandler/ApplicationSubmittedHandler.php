<?php

namespace App\MessageHandler;

use App\Entity\Application\Application;
use App\Message\ApplicationSubmitted;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ApplicationSubmittedHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private EmailService $emailService,
    ) {
    }

    public function __invoke(ApplicationSubmitted $message): void
    {
        $applicationId = $message->getApplicationId();
        $application = $this->em->getRepository(Application::class)->find($applicationId);
        $this->emailService->sendApplicationEmail($application);
    }
}
