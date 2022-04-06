<?php

namespace App\Console\Commands;

use App\Jobs\FetchDataJob;
use Illuminate\Console\Command;

class FetchDataCommand extends Command
{
    protected $signature = 'data:fetch';

    protected $description = 'Get data from external XML';

    public function handle()
    {
        FetchDataJob::dispatch();
    }
}
