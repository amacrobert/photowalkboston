<?php

namespace App\Service;

use App\Entity\Event;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarService
{
    private $request_stack;
    private $url_generator;
    private $start;
    private $end;
    private $title;
    private $description;
    private $location;

    public function __construct(
        RequestStack $request_stack,
        UrlGeneratorInterface $url_generator
    )
    {
        $this->request_stack = $request_stack;
        $this->url_generator = $url_generator;
    }

    public function configure(
        \DateTime $start,
        \DateTime $end,
        string $title,
        string $description,
        string $location
    ): void
    {
        $this->start = (clone $start)->add($this->getTimezoneOffset());
        $this->end = (clone $end)->add($this->getTimezoneOffset());
        $this->title = 'Photo Walk Boston: ' . $title;
        $this->description = $description;
        $this->location = $location;
    }

    public function getGoogleLink(): string
    {
        $datetime_format = 'Ymd\THis\Z';
        // dd($this->start->format($datetime_format), $this->end->format($datetime_format));

        return
            'https://calendar.google.com/calendar/render' .
            '?action=TEMPLATE' .
            sprintf(
                '&dates=%s/%s',
                urlencode($this->start->format($datetime_format)),
                urlencode($this->end->format($datetime_format))
            ) .
            '&details=' . urlencode($this->description) .
            '&location=' . urlencode($this->location) .
            '&text=' . urlencode($this->title);
    }

    public function getOutlookLink(): string
    {
        $datetime_format = 'Y-m-d\TH:m:s';

        return
            'https://outlook.live.com/calendar/0/deeplink/compose' .
            '?allday=false' .
            '&body=' . urlencode($this->description) .
            '&enddt=' . urlencode($this->start->format($datetime_format)) .
            '&location=' . urlencode($this->location) .
            '&path=%2Fcalendar%2Faction%2Fcompose' .
            '&rru=addevent' .
            '&startdt=' . urlencode($this->end->format($datetime_format)) .
            '&subject=' . urlencode($this->title);
    }

    public function getOffice365Link(): string
    {
        $datetime_format = 'Y-m-dTH:m:s';

        return
            'https://outlook.office.com/calendar/0/deeplink/compose' .
            '?allday=false' .
            '&body=' . urlencode($this->description) .
            '&enddt=' . urlencode($this->start->format($datetime_format)) .
            '&location=' . urlencode($this->location) .
            '&path=%2Fcalendar%2Faction%2Fcompose' .
            '&rru=addevent' .
            '&startdt=' . urlencode($this->end->format($datetime_format)) .
            '&subject=' . urlencode($this->title);
    }

    public function getIcsLink(Event $event): string
    {
        $request = $this->request_stack->getCurrentRequest();
        $url =
            $request->getSchemeAndHttpHost() .
            $this->url_generator->generate(
                'ics',
                ['event_id' => $event->getId()]
            );

        return $url;
    }

    public function getIcsContent(): string
    {
        $datetime_format = 'Ymd\THis\Z';

        return
            'BEGIN:VCALENDAR' . PHP_EOL .
            'VERSION:2.0' . PHP_EOL .
            'BEGIN:VEVENT' . PHP_EOL .
            'DTSTART:' . $this->start->format($datetime_format) . PHP_EOL .
            'DTEND:' . $this->end->format($datetime_format) . PHP_EOL .
            'SUMMARY:' . $this->title . PHP_EOL .
            'DESCRIPTION:' . str_replace("\n", "\\n", $this->description) . PHP_EOL . PHP_EOL .
            'LOCATION:' . $this->location . PHP_EOL .
            'END:VEVENT' . PHP_EOL .
            'END:VCALENDAR' . PHP_EOL;
    }

    private function getTimezoneOffset(): \DateInterval
    {
        $tz = date_default_timezone_get();
        date_default_timezone_set('America/New_York');
        $date = new \DateTime();
        $offset = $date->getOffset();
        date_default_timezone_set($tz);

        $offset_interval = new \DateInterval(sprintf('PT%sS', abs($offset)));

        if ($offset > 0) {
            $offset_interval->invert = 1;
        }

        return $offset_interval;
    }
}
