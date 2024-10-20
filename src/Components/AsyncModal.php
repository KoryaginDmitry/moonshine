<?php

declare(strict_types=1);

namespace MoonShine\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class AsyncModal extends Component
{
    public function __construct(public string $id, public string $route)
    {
        $this->id = (string) Str::of($id)->slug('_');
    }

    public function render(): View
    {
        return view('moonshine::components.async-modal');
    }
}
