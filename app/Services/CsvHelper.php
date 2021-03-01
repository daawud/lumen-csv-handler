<?php

namespace App\Services;

class CsvHelper implements FileHandlerInterface
{
    /**
     * @param string $filePath
     * @return ParsedFile
     */
    public function read(string $filePath): ParsedFile
    {
        $handle = fopen($filePath, "r");
    
        $lines = [];
        
        while (($line = fgetcsv($handle)) !== false) {
            $lines[] = $line;
        }
        
        fclose($handle);
        
        $header = array_shift($lines);
        
        return new ParsedFile($lines, $header);
    }
    
    /**
     * @param string $filePath
     * @param array $lines
     * @return mixed|void
     */
    public function write(string $filePath, array $lines)
    {
        $handle = fopen($filePath, "w");
    
        foreach ($lines as $line) {
            fputcsv($handle, $line);
        }
        
        fclose($handle);
    }
}