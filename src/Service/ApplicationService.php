<?php

namespace App\Service;

use App\Entity\Application\Application;
use App\Entity\Event;
use App\Message\ApplicationSubmitted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ApplicationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @param mixed[] $formValues
     */
    public function createApplication(array $formValues): Application
    {
        if (empty($formValues['name']) || empty($formValues['email'])) {
            throw new \Exception('Name and/or email missing');
        }

        if ($eventId = $formValues['eventId']) {
            $event = $this->em->getRepository(Event::class)->find($eventId);
        }

        $application = (new Application())
            ->setEvent($event ?? null)
            ->setName($formValues['name'])
            ->setEmail($formValues['email'])
            ->setPursuit($formValues['pursuit'] ?? null)
            ->setInstagram($formValues['instagram'] ?? null)
            ->setWebsite($formValues['website'] ?? null)
            ->setExperience($formValues['experience'] ?? null)
            ->setReferral($formValues['referral'] ?? null)
            ->setOpenResponse($formValues['openResponse'] ?? null);

        $this->em->persist($application);
        $this->em->flush();

        $this->messageBus->dispatch(new ApplicationSubmitted($application));

        return $application;
    }
}
