<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        URL::forceRootUrl(config('app.url'));

        FilamentView::registerRenderHook(PanelsRenderHook::SCRIPTS_AFTER, function (): string {
            return <<<'JS'
                <script>
                    document.addEventListener('wheel', function(e) {
                        const target = e.target.closest('input[type="number"]');
                        if (target && target === document.activeElement) {
                            e.preventDefault();
                        }
                    }, { passive: false });
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            const target = e.target.closest('.fi-ta-search-field input');
                            if (target) {
                                target.blur();
                            }
                        }
                    });
                </script>
            JS;
        });
    }
}
