<?php

namespace App\Console\Commands;

use App\Models\enquiry;
use Illuminate\Console\Command;

class RestoreStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore-stock-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $expiredEnquiries = enquiry::where('rental_end_date', '<', now())->get();

    foreach ($expiredEnquiries as $enquiry) {
        foreach ($enquiry->products as $product) {
            $product->increment('available_stock', $product->pivot->quantity);
        }

        // Optionally detach products if no longer needed
        $enquiry->products()->detach();
    }
}
}
