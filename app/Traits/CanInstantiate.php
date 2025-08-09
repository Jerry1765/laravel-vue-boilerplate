<?php

namespace App\Traits;

trait CanInstantiate
{
    public static function instance(): static
    {
        return app(self::class);
    }
}
