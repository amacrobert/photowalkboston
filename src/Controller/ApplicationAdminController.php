<?php

namespace App\Controller;

use App\Entity\Application\Application;
use App\Entity\Application\ApplicationStatus;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends CRUDController<Application>
 */
final class ApplicationAdminController extends CRUDController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function approveAction(): Response
    {
        return $this->setApplicationStatusAction(
            ApplicationStatus::Accepted
        );
    }

    public function rejectAction(): Response
    {
        return $this->setApplicationStatusAction(
            ApplicationStatus::Rejected
        );
    }

    private function setApplicationStatusAction(ApplicationStatus $status): Response
    {
        try {
            $application = $this->admin->getSubject();
        } catch (\LogicException $e) {
            throw new NotFoundHttpException('No application found', previous: $e);
        }

        $application->setStatus($status);
        $this->em->flush();

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
