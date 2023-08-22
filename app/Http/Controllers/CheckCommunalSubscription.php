<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

    use App\Models\Office;
    use Carbon\Carbon;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    
    class CheckCommunalSubscription implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
        public function handle()
        {
            $offices = Office::whereNotNull('created_at')->get();
    
            foreach ($offices as $office) {
                $communalPeriod = $office->communal_period;
                $createdAt = Carbon::parse($office->created_at);
                $now = Carbon::now();
    
                if ($now->diffInDays($createdAt) >= $communalPeriod) {
                    // Delete the office from the special schedule
                    // ...
                    
                    // Remove the communal subscription from the office
                    $office->created_at = null;
                    $office->communal_period = 0;
                    $office->save();
                }
            }
        }
    }

