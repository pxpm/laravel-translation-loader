<?php

namespace Spatie\TranslationLoader\Test;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadTranslationsFrom(__DIR__.'/lang', 'namespace-loaded');
    }
}
