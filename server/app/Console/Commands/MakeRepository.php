<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = str_replace('.php', '', $this->argument('name'));

        $interface_path = app_path("Core/Contracts/Repositories/{$name}Interface.php");
        $repository_path = app_path("Infrastructure/Persistence/Repositories/{$name}.php");

        if (File::exists($interface_path) && File::exists($repository_path)) {
            $this->error("Repository {$name} already exists.");
            return;
        }

        File::ensureDirectoryExists(dirname($interface_path));
        File::ensureDirectoryExists(dirname($repository_path));

        $module = "";

        if (str_contains($name, '/')) {
            $module = explode('/', $name)[0];
            $name = explode('/', $name)[1];
        }

        File::put($interface_path, $this->getInterfaceStub($name . 'Interface', $module));
        File::put($repository_path, $this->getRepositoryStub($name, $module));

        $this->updateAppServiceProvider("{$name}Interface", $name, $module);

        $this->info("Repository {$name} created successfully.");
    }

    private function getInterfaceStub($name, $module)
    {
        $namespace = "App\Core\Contracts\Repositories";

        if ($module) $namespace .= "\\{$module}";

        return <<<PHP
        <?php

        namespace {$namespace};

        interface {$name} extends RepositoryInterface
        {
            // Add repository methods here
        }
        PHP;
    }

    private function getRepositoryStub($name, $module)
    {
        $interface = "App\Core\Contracts\Repositories\\{$name}Interface";
        $namespace = "App\Infrastructure\Persistence\Repositories";

        if ($module) {
            $namespace .= "\\{$module}";
            $interface = "App\Core\Contracts\Repositories\\{$module}\\{$name}Interface";
        }

        return <<<PHP
        <?php

        namespace {$namespace};

        use Illuminate\Database\Eloquent\Model;
        use App\Core\Contracts\EntityInterface;
        use {$interface};

        class {$name} implements {$name}Interface
        {
            public function __construct() {}

            public function toEntity(Model \$model): EntityInterface
            {
                return new EntityInterface; // Replace with your entity implementation
            }
        }
        PHP;
    }

    private function updateAppServiceProvider($interface, $repository, $module)
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');

        $interfaceNamespace = "App\\Core\\Contracts\\Repositories\\$interface";
        $repositoryNamespace = "App\\Infrastructure\\Persistence\\Repositories\\$repository";

        if ($module) {
            $interfaceNamespace = "App\\Core\\Contracts\\Repositories\\{$module}\\$interface";
            $repositoryNamespace = "App\\Infrastructure\\Persistence\\Repositories\\{$module}\\$repository";
        }

        $useInterface = "use {$interfaceNamespace};";
        $useRepository = "use {$repositoryNamespace};";

        $bindingCode = "\t\t\$this->app->bind({$interface}::class, {$repository}::class);";

        $providerContent = File::get($providerPath);

        if (!str_contains($providerContent, $useInterface) && !str_contains($providerContent, $useRepository)) {
            $providerContent = preg_replace(
                '/(namespace\s+App\\\Providers;)/',
                "$1\n\n{$useInterface}\n{$useRepository}",
                $providerContent
            );
        }

        $providerContent = preg_replace(
            '/(public function register\(\): void\s*{)/',
            "$1\n$bindingCode",
            $providerContent
        );

        File::put($providerPath, $providerContent);
    }
}
