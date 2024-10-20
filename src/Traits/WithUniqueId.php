<?php

declare(strict_types=1);

namespace MoonShine\Traits;

use Illuminate\Support\Str;

trait WithUniqueId
{
    protected ?string $uniqueId = null;

    public function id(string $index = null): string
    {
        if (is_null($this->uniqueId)) {
            $this->uniqueId = (string) Str::of(Str::random())
                ->slug('_')
                ->when(! is_null($index), fn ($str) => $str->append('_' . $index));
        }

        return $this->uniqueId;
    }
}
