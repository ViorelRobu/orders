<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;

class PruneOrderAudits implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // set the time to 2 years ago
        $time = Carbon::now()->subYears(2)->toDateTimeString();

        // delete the audits for Order and OrderDetail models which are older than 2 years
        $audits = Audit::where('created_at', '<', $time)->whereIn('auditable_type', ['App\Order', 'App\OrderDetail'])->get();
        foreach($audits as $audit) {
            $audit->delete();
        }
    }
}
