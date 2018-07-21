<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshSeedDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zf:refreshdb {--S|seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $toSeed = $this->option('seed') || false;

        $this->call('migrate:reset');
        $this->call('migrate');

        if ($toSeed) {
            $this->call('db:seed');
        }
    }
}
