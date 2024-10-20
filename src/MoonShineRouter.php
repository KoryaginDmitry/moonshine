<?php

declare(strict_types=1);

namespace MoonShine;

use Illuminate\Support\Str;

final class MoonShineRouter
{
    public static function to(string $name, array $params = []): string
    {
        return route(
            Str::of($name)
                ->remove('moonshine.')
                ->prepend('moonshine.'),
            $params
        );
    }

    public static function uriKey(string $class): string
    {
        return (string) Str::of(class_basename($class))
            ->kebab();
    }
}
