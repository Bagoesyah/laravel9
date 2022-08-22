<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\WordpressController;

class Importer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $page = ($this->argument('page')) ? $this->argument('page') : 1;
        $this->WordpressController->importPosts($page);
    }
}
