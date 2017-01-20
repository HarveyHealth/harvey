<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeComponentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:component {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a Vue component file';

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
        $filename = $this->argument('filename');

        $template = "<template>

</template>

<script>
    // components

    export default {
        props: {
        },
        components: {
        },
        mounted() {
        }
    }
</script>

<style lang=\"sass\" scoped>

</style>

";

        // make sure we have .blade.php on the end
        str_replace('.vue', '', $filename);
        $filename .= '.vue';

        $full_path = resource_path() . '/assets/js/components/' . $filename;

        if ($this->files->exists($full_path)) {
            $this->error('File already exists!');
            return false;
        }

        // get just the path, not the filename
        $path_parts = pathinfo($full_path);
        $path = $path_parts['dirname'];

        // create the directory recursively
        if (! $this->files->isDirectory($path)) {
            mkdir($path, 0777, true);
        }

        // write the file
        $this->files->put($full_path, $template);

        $this->info('View created successfully.');
    }
}
