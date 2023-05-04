<?php

namespace App\Controller;

use App\Service\ApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    #[Route('/application', name: 'app_application', methods: ['POST'])]
    public function index(
        Request $request,
        ApplicationService $applicationService,
    ): Response {
        $response = new RedirectResponse($request->request->get('dest', '/'));

        if (!$request->request->has('application')) {
            return $response;
        }

        try {
            $application = $applicationService->createApplication($request->request->all());
            $this->addFlash(
                'success',
                sprintf(
                    '<strong>We got your application!</strong>' .
                    'It is now under review. If approved, you will be contacted at %s with %s.',
                    $application->getEmail(),
                    $application->getEvent() ? 'the password' : 'further instructions',
                )
            );
            $response->headers->setCookie(new Cookie('applicationPending', '1'));
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                sprintf('<strong>There was an error submitting your application</strong>: %s', $e->getMessage())
            );
        }

        return $response;
    }
}
