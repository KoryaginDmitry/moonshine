<?php

use Illuminate\Support\Facades\Route;
use MoonShine\Exceptions\MoonShineNotFoundException;
use MoonShine\Http\Controllers\AttachmentController;
use MoonShine\Http\Controllers\AuthenticateController;
use MoonShine\Http\Controllers\CustomPageController;
use MoonShine\Http\Controllers\DashboardController;
use MoonShine\Http\Controllers\NotificationController;
use MoonShine\Http\Controllers\ProfileController;
use MoonShine\Http\Controllers\SearchController;
use MoonShine\Http\Controllers\SocialiteController;

$middlewares = collect(config('moonshine.route.middleware'))
    ->reject(fn($middleware): bool => $middleware === 'web')
    ->toArray();

Route::prefix(config('moonshine.route.prefix', ''))
    ->middleware($middlewares)
    ->as('moonshine.')->group(static function () {
        Route::middleware('auth.moonshine')->group(function (): void {
            Route::get('/', DashboardController::class)->name('index');
            Route::post('/attachments', AttachmentController::class)->name('attachments');

            Route::get('/search/relations', [SearchController::class, 'relations'])
                ->name('search.relations');

            Route::prefix('notifications')
                ->as('notifications.')
                ->group(static function (): void {
                    Route::get('/', [NotificationController::class, 'readAll'])->name('readAll');
                    Route::get('/{notification}', [NotificationController::class, 'read'])->name('read');
                });


            Route::get(
                config('moonshine.route.custom_page_slug', 'custom_page').'/{alias}',
                CustomPageController::class
            )->name('custom_page');
        });

        if (config('moonshine.auth.enable', true)) {
            Route::get('/login', [AuthenticateController::class, 'login'])->name('login');
            Route::post('/authenticate', [AuthenticateController::class, 'authenticate'])->name('authenticate');
            Route::get('/logout', [AuthenticateController::class, 'logout'])->name('logout');

            Route::prefix('socialite')
                ->as('socialite.')
                ->group(static function (): void {
                    Route::get('/{driver}/redirect', [SocialiteController::class, 'redirect'])->name('redirect');
                    Route::get('/{driver}/callback', [SocialiteController::class, 'callback'])->name('callback');
                });

            Route::post('/profile', [ProfileController::class, 'store'])
                ->middleware('auth.moonshine')
                ->name('profile.store');
        }

        Route::fallback(static function () {
            $handler = config(
                'moonshine.route.notFoundHandler',
                MoonShineNotFoundException::class
            );

            throw new $handler();
        });
    });
