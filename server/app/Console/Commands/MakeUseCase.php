<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeUseCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:usecase {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new use case class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Core/UseCases/{$name}.php");

        if (File::exists($path)) {
            $this->error("Use case {$name} already exists.");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->getStub($name));

        $this->info("Use case {$name} created successfully.");
    }

    private function getStub($name)
    {
        $namespace = "App\Core\UseCases";

        if (str_contains($name, '/')) {
            $module = explode('/', $name)[0];
            $name = explode('/', $name)[1];

            $namespace .= "\\{$module}";
        }

        return <<<PHP
        <?php

        namespace {$namespace};

        use App\Support\Traits\ThrowException;

        class {$name}
        {
            use ThrowException;

            public function execute()
            {
                // Implementation
            }
        }
        PHP;
    }
}
