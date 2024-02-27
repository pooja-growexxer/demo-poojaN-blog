<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CacheCleanOnce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'all:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Cache Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('cache:clear');
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('route:clear');
        $this->call('optimize:clear');
        $this->call('optimize');
        return Command::SUCCESS;
    }
}
