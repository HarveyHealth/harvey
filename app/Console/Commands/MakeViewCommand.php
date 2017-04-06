<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {filename} {--layout=logged_in}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Blade view';

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
        $layout = $this->option('layout');

        if (empty($layout)) {
            $layout = 'logged_in';
        }

        $template = "@extends('legacy._layouts.{$layout}')
@section('page_title','')

@push('stylesheets')
    {{-- use @stylesheet(path/to/style.css) here --}}
@endpush

@push('scripts')
    {{-- use @script(path/to/script.js) here --}}
@endpush




@section('content')

{{-- content goes here --}}

@endsection

";

        // make sure we have .blade.php on the end
        str_replace('.blade.php', '', $filename);
        $filename .= '.blade.php';

        $full_path = resource_path() . '/views/' . $filename;

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
