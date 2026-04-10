<?php

namespace App\Tests\Functional;

use App\Factory\EventFactory;
use App\Factory\ImageFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class EventPageTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    /**
     * @testdox The event page loads and displays the event title and date
     */
    public function testEventPageDisplaysTitleAndDate(): void
    {
        $client = static::createClient();

        $event = EventFactory::createOne([
            'title' => 'Sunset at the Harbor',
            'date' => new \DateTime('2026-05-15'),
            'description' => 'A beautiful walk through the harbor at sunset.',
            'meeting_location' => 'Boston Harbor',
            'banner_image' => ImageFactory::createOne(),
        ]);

        $crawler = $client->request('GET', '/events/' . $event->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1.event-page--title', 'Sunset at the Harbor');
        $this->assertSelectorTextContains('.event-page--title-date', 'May 15th, 2026');
        $this->assertSelectorTextContains('.lead', 'A beautiful walk through the harbor at sunset.');
    }
}
