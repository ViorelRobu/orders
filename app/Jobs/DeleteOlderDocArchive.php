<?php

namespace App\Jobs;

use App\DocArchive;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteOlderDocArchive implements ShouldQueue
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
        $lastWeek = Carbon::now()->subWeek();
        // select all imports and exports which are older than 7 days
        $docs = DocArchive::where('created_at', '<', $lastWeek)->get();
        // delete the file and table entry
        foreach ($docs as $doc) {
            Storage::disk($doc->type . 's')->delete($doc->document);
            $doc->delete();
        }
    }
}
