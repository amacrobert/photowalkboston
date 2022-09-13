<?php

namespace App\Tests\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Photo Walk Boston');
    }

    public function testEventPage(): void
    {
        $client = static::createClient();
        $event = $this->testEventFixture();
        $crawler = $client->request('GET', sprintf('/events/%s', $event->getId()));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'The Forgotten Test Ave');
    }

    // A user who hasn't entered the event password shouldn't be able to download its ics
    public function testIcsDownloadDenied(): void
    {
        $client = static::createClient();
        $event = $this->testEventFixture();
        $crawler = $client->request('GET', sprintf('/ics/%s', $event->getId()));
        $this->assertResponseStatusCodeSame(403);
    }

    // A user may go to a password-protected event page, enter the password, then download the ics
    public function testPasswordSubmissionAndIcsDownload(): void
    {
        $client = static::createClient();
        $event = $this->testEventFixture();
        $crawler = $client->request('GET', sprintf('/events/%s', $event->getId()));

        $password_submit_button = $crawler->selectButton('Submit');
        $form = $password_submit_button->form();

        // Get the name of the password field (it's different for each event) and use it to submit the pass
        $fields = $form->all();
        $form->setValues([array_pop($fields)->getName() => 'goldenhour']);
        $client->submit($form);

        // Assert that submitting the correct password results in an "Access granted" alert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Access granted');

        // Assert that the user is now able to download the ics for the event
        $client->request('GET', sprintf('/ics/%s', $event->getId()));
        $this->assertResponseIsSuccessful();
    }

    private function testEventFixture(): Event
    {
        $event = (new Event())
            ->setTitle('The Forgotten Test Ave')
            ->setDescription('Come join us for a photo walk in this little-known Boston nook')
            ->setDate(new \DateTime)
            ->setPassword('goldenhour')
            ->setMeetingLocation('Corner of Test Ave and Prod St')
            ->setMeetingInstructions('Look for the statue of the FSM')
            ->setParking('Street parking available')
            ->setModelTheme('Wear your favorite meatsuit')
            ->setPhotographerChallenge('Shoot with disposable cameras');

        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->persist($event);
        $em->flush();
        $em->clear();

        return $event;
    }
}
