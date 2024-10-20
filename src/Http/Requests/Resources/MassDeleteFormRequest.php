<?php

declare(strict_types=1);

namespace MoonShine\Http\Requests\Resources;

use Illuminate\Support\Str;
use MoonShine\MoonShineRequest;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class MassDeleteFormRequest extends MoonShineRequest
{
    public function authorize(): bool
    {
        return $this->getResource()->can('massDelete');
    }

    /**
     * @return array{ids: string[]}
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'ids' => Str::of(request()->get('ids'))
                ->explode(';')
                ->filter()
                ->toArray(),
        ]);
    }
}
