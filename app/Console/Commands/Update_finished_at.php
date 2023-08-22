<?php

namespace App\Console\Commands;

use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Update_finished_at extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:finished_at';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'for update the date of office by Year';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offices = Office::all();
        foreach ($offices as $office) {

            $createdDate = Carbon::parse(strtotime($office->created_at))->addYear();

            $afterOneYear = $createdDate->format('Y-m-d');
            Office::where('id', $office->id)->update(['finished_at' => $afterOneYear]);

        }
    }
}