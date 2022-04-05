<?php

namespace App\Console\Commands;

use App\Jobs\FetchDataJob;
use Illuminate\Console\Command;

class testCommand extends Command
{
    protected $signature = 'command:name';

    protected $description = 'Command description';

    public function handle()
    {
        FetchDataJob::dispatch();
    }
}
