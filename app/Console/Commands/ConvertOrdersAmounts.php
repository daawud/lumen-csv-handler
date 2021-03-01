<?php

namespace App\Console\Commands;

use App\Services\Converter;
use App\Services\CsvHelper;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConvertOrdersAmounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:convert {currency} {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'converting amounts in orders';
    
    /**
     * @return void
     */
    public function handle(): void
    {
        $currency = $this->argument('currency');
        $date = $this->argument('date');
    
        try {
            $files = Storage::files('orders');
            $converter = new Converter(new CsvHelper());
            
            foreach ($files as $file) {
                $converter->convert(Storage::path($file), Carbon::parse($date), $currency);
            }
            
        } catch (Exception $e) {
            Log::error('orders-amounts-converting-error', [$e]);
        }
    }
}