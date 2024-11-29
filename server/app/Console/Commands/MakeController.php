<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:new-controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = str_replace('.php', '', $this->argument('name'));

        $path = app_path("Http/Controllers/{$name}.php");

        if (File::exists($path)) {
            $this->error("Controller {$name} already exists.");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->getStub($name));

        $this->info("Controller {$name} created successfully.");
    }

    private function getStub($name)
    {
        $namespace = "App\Http\Controllers";

        if (str_contains($name, '/')) {
            $module = explode('/', $name)[0];
            $name = explode('/', $name)[1];

            $namespace .= "\\{$module}";
        }

        return <<<PHP
        <?php

        namespace {$namespace};

        use Illuminate\Http\JsonResponse;
        use Illuminate\Http\Request;
        use App\Support\HttpResponse;

        class {$name} extends Controller
        {
            public function __construct(
                private HttpResponse \$httpResponse
            ) {}

            public function index(): JsonResponse
            {
                return \$this->httpResponse->data([]);
            }

            public function show(int \$id): JsonResponse
            {
                return \$this->httpResponse->data([]);
            }

            public function store(Request \$request): JsonResponse
            {
                return \$this->httpResponse->message('');
            }

            public function update(Request \$request): JsonResponse
            {
                return \$this->httpResponse->message('');
            }

            public function destroy(int \$id): JsonResponse
            {
                return \$this->httpResponse->message('');
            }
        }
        PHP;
    }
}
