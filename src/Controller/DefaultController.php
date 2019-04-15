<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;

class DefaultController extends Controller {

    const ApplicationQuestions = [
        'Your full name',
        'Instagram link',
        'Are you primarily a photographer or model?',
        'How did you hear about us?',
        'Website link',
        'Level of experience',
        'Anything else you\'d like to share',
    ];

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
            ->setParameters(['cutoff' => new \DateTime('24 hours ago')])
            ->setMaxResults(3);
        $events = $query->getResult();

        return $this->render('index.html.twig', [
            'events' => $events,
            'applicationQuestions' => self::ApplicationQuestions,
        ]);
    }

    /**
     * @Route("/events/{event_id}", name="event")
     */
    public function event($event_id, EntityManagerInterface $em, Request $request) {

        $event = $em->find(Event::class, $event_id);

        $password = strtolower($event->getPassword());
        $cookie_name = 'event_' . $event->getId() . '_pass';
        parse_str($request->getContent(), $post_vals);
        $posted_pass = isset($post_vals[$cookie_name]) ? strtolower($post_vals[$cookie_name]) : null;

        $event_protected = (bool)$password;
        $cookie_matches_pass = $request->cookies->get($cookie_name) == $password;
        $post_matches_pass = $posted_pass && $posted_pass == $password;

        $has_access = !$event_protected || $cookie_matches_pass || $post_matches_pass;

        $response = $this->render('event.html.twig', [
            'event' => $event,
            'has_access' => $has_access,
            'password_wrong' => $posted_pass && !$post_matches_pass,
            'password_right' => $posted_pass && $post_matches_pass,
            'applicationQuestions' => array_merge(['Event: ' . $event->getTitle()], self::ApplicationQuestions),
        ]);

        if ($post_matches_pass) {
            $response->headers->setCookie(new Cookie($cookie_name, $posted_pass));
        }

        return $response;
    }

    protected function render(
        string $view,
        array $parameters = array(),
        Response $response = null
    ): Response {
        $parameters['host'] = $_SERVER['HTTP_HOST'];
        $parameters['now'] = new \DateTime;
        return parent::render($view, $parameters);
    }

}
