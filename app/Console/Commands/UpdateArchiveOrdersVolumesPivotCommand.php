<?php

namespace App\Console\Commands;

use App\Jobs\UpdateArchiveOrdersVolumesPivot;
use Illuminate\Console\Command;

class UpdateArchiveOrdersVolumesPivotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:pivot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update/Insert the volumes in the archived_orders_volumes table';

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
        dispatch(new UpdateArchiveOrdersVolumesPivot);
    }
}
