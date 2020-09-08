<?php

namespace App\Console\Commands;

use App\Jobs\DeleteOlderDocArchive;
use Illuminate\Console\Command;

class DeleteOlderDocArchiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docArchive:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete imports and exports which are older than one week';

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
        dispatch(new DeleteOlderDocArchive());
    }
}
