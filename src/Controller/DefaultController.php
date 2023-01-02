<?php

namespace App\Controller;

use App\Entity\Event;
use App\Service\CalendarService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private const APPLICATION_QUESTIONS = [
        'Your full name',
        'Instagram link',
        'Are you primarily a photographer or model?',
        'How did you hear about us?',
        'Website link',
        'Level of experience',
        'Anything else you\'d like to share',
    ];

    public function __construct(private RequestStack $requestStack)
    {
    }

    #[Route(path: '/', name: 'index', methods: 'GET')]
    public function index(EntityManagerInterface $em): Response
    {
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

        return $this->renderWithHostData('index.html.twig', [
            'events' => $events,
            'applicationQuestions' => self::APPLICATION_QUESTIONS,
        ]);
    }

    #[Route(path: '/events/{event_id}', name: 'event', methods: ['GET', 'POST'])]
    public function event(
        string $event_id,
        EntityManagerInterface $em,
        Request $request,
        CalendarService $calendar_service
    ): Response
    {
        if (!$event = $em->find(Event::class, $event_id)) {
            return new Response(null, 404);
        }

        $password = strtolower($event->getPassword());
        $cookie_name = 'event_' . $event->getId() . '_pass';
        parse_str($request->getContent(), $post_vals);
        $posted_pass = isset($post_vals[$cookie_name]) ? strtolower($post_vals[$cookie_name]) : null;

        $event_protected = (bool)$password;
        $cookie_matches_pass = $request->cookies->get($cookie_name) == $password;
        $post_matches_pass = $posted_pass && $posted_pass == $password;

        $has_access = !$event_protected || $cookie_matches_pass || $post_matches_pass;

        $this->configureCalendarForEvent($calendar_service, $event);

        $response = $this->renderWithHostData('event.html.twig', [
            'event' => $event,
            'has_access' => $has_access,
            'password_wrong' => $posted_pass && !$post_matches_pass,
            'password_right' => $posted_pass && $post_matches_pass,
            'applicationQuestions' => array_merge(['Event: ' . $event->getTitle()], self::APPLICATION_QUESTIONS),
            'calendar' => [
                'google' => $calendar_service->getGoogleLink(),
                'outlook' => $calendar_service->getOutlookLink(),
                'office365' => $calendar_service->getOffice365Link(),
                'ics' => $calendar_service->getIcsLink($event),
            ],
        ]);

        if ($post_matches_pass) {
            $response->headers->setCookie(new Cookie($cookie_name, $posted_pass));
        }

        return $response;
    }

    #[Route(path: '/ics/{event_id}', name: 'ics', methods: 'GET')]
    public function icsDownload(
        string $event_id,
        CalendarService $calendar_service,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        if (!$event = $em->find(Event::class, $event_id)) {
            return new Response(null, 404);
        }

        $password = strtolower($event->getPassword());
        $cookie_name = 'event_' . $event->getId() . '_pass';
        $cookie_matches_pass = $request->cookies->get($cookie_name) == $password;

        // The user must have entered the correct password to download the invite
        if (!$cookie_matches_pass) {
            return new Response(null, 403);
        }

        $this->configureCalendarForEvent($calendar_service, $event);

        $response = new Response($calendar_service->getIcsContent());
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'download.ics'
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    /**
     * @param array<mixed> $parameters
     */
    protected function renderWithHostData(
        string $view,
        array $parameters = [],
        Response $response = null
    ): Response {
        $parameters['host'] = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $parameters['now'] = new \DateTime();
        return parent::render($view, $parameters);
    }

    private function configureCalendarForEvent(CalendarService $calendar_service, Event $event): void
    {
        $start = $event->getDate();
        $end = (clone $start)->add(new \DateInterval('PT3H'));

        $calendar_event_description = $event->getDescription() . PHP_EOL . PHP_EOL;
        if ($event->getMeetingInstructions()) {
            $calendar_event_description .= 'Meeting instructions: ' . $event->getMeetingInstructions() . PHP_EOL .
                PHP_EOL;
        }
        if ($event->getParking()) {
            $calendar_event_description .= 'Parking instructions: ' . $event->getParking() . PHP_EOL . PHP_EOL;
        }
        if ($event->getModelTheme()) {
            $calendar_event_description .= 'Optional model theme: ' . $event->getModelTheme() . PHP_EOL . PHP_EOL;
        }
        if ($event->getPhotographerChallenge()) {
            $calendar_event_description .= 'Optional photographer challenge: ' . $event->getPhotographerChallenge() .
                PHP_EOL . PHP_EOL;
        }
        $calendar_event_description .= 'Photo Walk Boston is a free intermittent meetup in Boston for photographers ' .
            'and models to practice their art in a collaborative, no-pressure environment.' . PHP_EOL . PHP_EOL;
        $calendar_event_description .= 'This is a meetup for people age 18 and up. This group is for adults to ' .
            'practice, network, learn and socialize. It is not intended to be a family- or child-friendly outing.';

        $calendar_service->configure(
            $start,
            $end,
            $event->getTitle(),
            $calendar_event_description,
            $event->getMeetingLocation()
        );
    }
}
