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

        $dql = "
            SELECT event
            FROM App\Entity\Event event
            WHERE event.date > :cutoff
            ORDER BY event.date ASC
        ";

        $query = $em
            ->createQuery($dql)
            ->setParameters(['cutoff' => new \DateTime('3 hours ago')])
            ->setMaxResults(3);
        $events = $query->getResult();

        return $this->render('index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/events/{event_id}", name="event")
     */
    public function event($event_id, EntityManagerInterface $em) {

        $event = $em->find(Event::class, $event_id);

        return $this->render('event.html.twig', [
            'event' => $event,
        ]);
    }

    protected function render(
        string $view,
        array $parameters = array(),
        Response $response = null): Response
    {
        $parameters['host'] = $_SERVER['HTTP_HOST'];
        $parameters['now'] = new \DateTime;
        return parent::render($view, $parameters);
    }

}
