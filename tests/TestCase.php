<?php

namespace Spatie\TranslationLoader\Test;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\TranslationLoader\LanguageLine;
use Spatie\TranslationLoader\TranslationServiceProvider;

abstract class TestCase extends Orchestra
{
    /** @var LanguageLine */
    protected $languageLine;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');

        $LanguageLinesTable = require __DIR__.'/../database/migrations/0000_00_00_000000_create_language_lines_table.php.stub';

        $LanguageLinesTable->up();

        $languageLinesAlterTable = require __DIR__.'/../database/migrations/9999_99_99_000001_alter_language_lines_table.php.stub';

        $languageLinesAlterTable->up();

        $this->languageLine = $this->createLanguageLine('group', 'key', ['en' => 'english', 'nl' => 'nederlands']);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TranslationServiceProvider::class,
            TestServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['path.lang'] = $this->getFixturesDirectory('lang');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function getFixturesDirectory(string $path): string
    {
        return __DIR__."/fixtures/{$path}";
    }

    protected function createLanguageLine(string $group, string $key, array $text): LanguageLine
    {
        return LanguageLine::create(compact('group', 'key', 'text'));
    }

    protected function createNamespacedLanguageLine(string $namespace, string $group, string $key, array $text): LanguageLine
    {
        return LanguageLine::create(compact('namespace', 'group', 'key', 'text'));
    }
}
