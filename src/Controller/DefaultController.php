<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;

class DefaultController extends Controller {

    /**
     * @Route("/", name="index")
     */
    public function index(EntityManagerInterface $em) {

        $events = $em->getRepository(Event::class)->findAll();

        return $this->render('base.html.twig', [
            'events' => $events,
        ]);
    }

}
