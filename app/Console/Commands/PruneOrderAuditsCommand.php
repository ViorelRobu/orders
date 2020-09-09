<?php

namespace App\Console\Commands;

use App\Jobs\PruneOrderAudits;
use Illuminate\Console\Command;

class PruneOrderAuditsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audits:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the audits for Order and OrderDetails model which are older than 2 years.';

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
     * @return int
     */
    public function handle()
    {
        dispatch(new PruneOrderAudits);
    }
}
