<?php

namespace App;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\FolioManager route(?string $path = null, ?string $uri = '/', array $middleware = [])
 */
class Folio extends Facade
{
    /**
     * {@inheritDoc}     .
     */
    public static function getFacadeAccessor(): string
    {
        return FolioManager::class;
    }
}