<?php

namespace App\Factory;

use App\Entity\Image;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Image>
 */
final class ImageFactory extends PersistentObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Image::class;
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    protected function defaults(): array
    {
        return [
            'filename' => '/images/' . self::faker()->word() . '.jpg',
            'credit' => self::faker()->name(),
            'updated' => self::faker()->dateTime(),
        ];
    }
}
