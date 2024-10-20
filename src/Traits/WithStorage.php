<?php

declare(strict_types=1);

namespace MoonShine\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait WithStorage
{
    protected string $disk = 'public';

    protected string $dir = '/';

    public function dir(string $dir): static
    {
        $this->dir = $dir;

        return $this;
    }

    protected function resolveStorage(): void
    {
        if (! Storage::disk($this->getDisk())->exists($this->getDir())) {
            Storage::disk($this->getDisk())->makeDirectory($this->getDir());
        }
    }

    public function disk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }

    public function getDir(): string
    {
        return (string) Str::of($this->dir)
            ->trim('/');
    }
}
