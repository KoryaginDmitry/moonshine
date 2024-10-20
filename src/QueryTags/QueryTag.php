<?php

declare(strict_types=1);

namespace MoonShine\QueryTags;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use MoonShine\Traits\HasCanSee;
use MoonShine\Traits\Makeable;
use MoonShine\Traits\WithIcon;
use MoonShine\Traits\WithLabel;

/**
 * @method static static make(string $label, Builder|Closure $builder)
 */
final class QueryTag
{
    use Makeable;
    use WithIcon;
    use HasCanSee;
    use WithLabel;

    /**
     * @deprecated Builder $builder, use Closure $builder
     */
    public function __construct(
        string $label,
        protected Builder|Closure $builder,
    ) {
        $this->setLabel($label);
    }

    public function uri(): string
    {
        return Str::of($this->label())->slug();
    }

    public function builder(Builder $builder): Builder
    {
        return is_callable($this->builder)
            ? call_user_func($this->builder, $builder)
            : $this->builder;
    }
}
