<?php

declare(strict_types=1);

namespace MoonShine\Commands;

use Illuminate\Support\Facades\Artisan;
use MoonShine\Providers\MoonShineServiceProvider;

class InstallCommand extends MoonShineCommand
{
    protected $signature = 'moonshine:install';

    protected $description = 'Install the moonshine package';

    public function handle(): void
    {
        $this->info('MoonShine installation ...');

        $this->initVendorPublish();
        $this->initStorage();
        $this->initServiceProvider();
        $this->initDirectories();
        $this->initMigrations();

        $this->info('Installation completed');

        if (! app()->runningUnitTests()) {
            $this->choice('Can you quickly star our GitHub repository? 🙏🏻', [
                'yes',
                'no',
            ], 'yes');
        }

        $this->info("Now run 'php artisan moonshine:user'");
    }

    protected function initVendorPublish(): void
    {
        Artisan::call('vendor:publish', [
            '--provider' => MoonShineServiceProvider::class,
            '--force' => true,
        ]);
    }

    protected function initStorage(): void
    {
        Artisan::call('storage:link');
    }

    protected function initServiceProvider(): void
    {
        $this->comment('Publishing MoonShine Service Provider...');
        Artisan::call('vendor:publish', ['--tag' => 'moonshine-provider']);

        if (! app()->runningUnitTests()) {
            $this->registerServiceProvider();
        }
    }

    protected function registerServiceProvider(): void
    {
    }

    protected function initDirectories(): void
    {
        if (is_dir($this->getDirectory())) {
            $this->warn(
                "{$this->getDirectory()} directory already exists!"
            );
        }
    }

    protected function initMigrations(): void
    {
        if (config('moonshine.use_migrations', true)) {
            Artisan::call('migrate');
        }
    }
}
