<?php

declare(strict_types=1);

namespace MoonShine\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use MoonShine\MoonShine;

class ResourceCommand extends MoonShineCommand
{
    protected $signature = 'moonshine:resource {name?} {--m|model=} {--t|title=} {--s|singleton} {--id=}';

    protected $description = 'Create resource';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->createResource();
    }

    /**
     * @throws FileNotFoundException
     */
    public function createResource(): void
    {
        $name = Str::of($this->argument('name') ?? $this->ask('Name'));
        $id = null;

        if ($this->option('singleton')) {
            $id = $this->option('id')
                ?? $this->ask('Item id', 1);
        }

        $name = $name->ucfirst()
            ->replace(['resource', 'Resource'], '');

        $model = $this->qualifyModel($this->option('model') ?? $name);
        $title = $this->option('title') ??
            ($this->option('singleton') ? $name
                : Str::of($name)->singular()->plural());

        $resource = $this->getDirectory() . "/Resources/{$name}Resource.php";

        $stub = $this->option('singleton')
            ? 'SingletonResource'
            : 'Resource';

        $this->copyStub($stub, $resource, [
            '{namespace}' => MoonShine::namespace('\Resources'),
            '{model-namespace}' => $model,
            '{model}' => class_basename($model),
            '{id}' => $id,
            'DummyTitle' => $title,
            'Dummy' => $name,
        ]);

        $this->info(
            "{$name}Resource file was created: " . str_replace(
                base_path(),
                '',
                $resource
            )
        );
        $this->info('Now register resource in menu');
    }
}
