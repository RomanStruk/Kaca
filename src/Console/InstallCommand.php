<?php

declare(strict_types=1);

namespace Kaca\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'kaca:install {stack : The development stack that should be installed}';

    protected $description = 'Kaca installing';

    public function handle(): int
    {
        $this->comment('Publishing Kaca Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'kaca-provider']);
        $this->comment('Publishing Kaca Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'kaca-config']);
        $this->comment('Publishing Kaca Migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'kaca-install-migrations']);

        // Install Stack...
        $this->comment('Publishing Kaca Resources...');
        $stack = $this->argument('stack');
        if ($stack === 'adminlte3'){
            $this->replaceInFile(
                "'stack' => 'tailwind',",
                "'stack' => 'adminlte3',",
                config_path('kaca.php')
            );
        }
        $this->callSilent('vendor:publish', ['--tag' => 'kaca-views-' . $stack, '--force' => true]);

        $this->registerKacaServiceProvider();
        return 0;
    }

    /**
     * Register the Telescope service provider in the application configuration file.
     */
    protected function registerKacaServiceProvider(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace . '\\Providers\\KacaApplicationServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class," . PHP_EOL,
            "{$namespace}\\Providers\RouteServiceProvider::class," . PHP_EOL . "        {$namespace}\Providers\KacaApplicationServiceProvider::class," . PHP_EOL,
            $appConfig
        ));

        file_put_contents(app_path('Providers/KacaApplicationServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/KacaApplicationServiceProvider.php'))
        ));
    }

    /**
     * Replace a given string within a given file.
     *
     * @param string $search
     * @param string $replace
     * @param string $path
     * @return void
     */
    protected function replaceInFile(string $search, string $replace, string $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
