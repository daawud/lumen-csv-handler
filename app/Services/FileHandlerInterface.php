<?php

namespace App\Services;

interface FileHandlerInterface
{
    /**
     * @param string $filePath
     * @return ParsedFile
     */
    public function read(string $filePath): ParsedFile;
    
    /**
     * @param string $filePath
     * @param array $lines
     * @return mixed
     */
    public function write(string $filePath, array $lines);
}