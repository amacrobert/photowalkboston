<?php

namespace App\Factory;

use App\Entity\Event;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Event>
 */
final class EventFactory extends PersistentObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Event::class;
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    protected function defaults(): array
    {
        return [
            'date' => self::faker()->dateTimeBetween('now', '+30 days'),
            'title' => self::faker()->text(),
        ];
    }
}
