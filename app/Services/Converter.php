<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class Converter
{
    /**
     * @var FileHandlerInterface
     */
    private FileHandlerInterface $handler;
    
    /**
     * Converter constructor.
     * @param FileHandlerInterface $handler
     */
    public function __construct(FileHandlerInterface $handler)
    {
        $this->handler = $handler;
    }
    
    /**
     * @param string $filePath
     * @param Carbon $date
     * @param string $currencyName
     * @throws Exception
     */
    public function convert(string $filePath, Carbon $date, string $currencyName): void
    {
        $currency = strtoupper($currencyName);
        $usdRate = $this->getRate($date, $currency);
        $parsedFile = $this->handler->read($filePath);
        $newLines = [];
        
        if ($parsedFile->completeHeader($currency)) {
            $newLines[] = $parsedFile->getHeader();
        }
        
        while ($currentAmount = $parsedFile->currentAmount()) {
            $oldLine = $parsedFile->currentLine();
            $oldLine[] = (string) round($currentAmount * $usdRate, 2);
            $newLines[] = $oldLine;
            $parsedFile->nextLine();
        }
        
        $this->handler->write($filePath, $newLines);
    }
    
    /**
     * @param Carbon $date
     * @param string $currencyName
     * @return mixed|null
     * @throws Exception
     */
    protected function getRate(Carbon $date, string $currencyName)
    {
        $response = Http::get(env('EXCHANGE_RATES_API_URL') . $date->toDateString(), [
            'base' => 'RUB',
        ]);
    
        if (!$response->ok()) {
            throw new Exception($response->body());
        }
    
        return $response->json()['rates'][$currencyName] ?? null;
    }
}