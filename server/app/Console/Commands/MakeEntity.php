<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:entity {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new entity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = str_replace('.php', '', $this->argument('name'));

        $path = app_path("Core/Domain/Entities/{$name}.php");

        if (File::exists($path)) {
            $this->error("Entity {$name} already exists.");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->getStub($name));

        $this->info("Entity {$name} created successfully.");
    }

    private function getStub($name)
    {
        return <<<PHP
        <?php

        namespace App\Core\Domain\Entities;

        use App\Core\Contracts\EntityInterface;

        class {$name} implements EntityInterface
        {
            public function __construct() {}

            public function toArray(): array
            {
                return [];
            }
        }
        PHP;
    }
}
