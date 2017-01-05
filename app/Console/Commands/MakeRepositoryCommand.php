<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {modelname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Repository class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = $this->argument('modelname');

        // make sure Repository is part of the class name
        $classname = $model . 'Repository';
        $full_path = app_path() . "/Repositories/{$classname}.php";

        // make sure this file doesn't already exist
        if ($this->files->exists($full_path)) {
            $this->error("File {$full_path} already exists!");
            return false;
        }



        $content = "<?php

namespace App\Repositories;

use App\Models\\{$model};


class {$classname} extends BaseRepository
{
    protected \$model = \App\Models\\{$model}::class;
}

";

        // write the file
        $this->files->put($full_path, $content);

        $this->info('Repository created successfully.');

    }
}
