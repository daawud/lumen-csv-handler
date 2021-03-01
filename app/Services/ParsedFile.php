<?php

namespace App\Services;

class ParsedFile
{
    const AMOUNT_COLUMN_NUMBER = 2;
    
    /**
     * @var array
     */
    private array $lines;
    
    /**
     * @var array
     */
    private array $header;
    
    /**
     * @var int
     */
    private int $amountColumnNumber;
    
    /**
     * ParsedFile constructor.
     * @param array $lines
     * @param array $header
     */
    public function __construct(array $lines, array $header = [])
    {
        $this->lines = $lines;
        $this->header = $header;
    }
    
    /**
     * @param string $columnName
     * @return bool
     */
    public function completeHeader(string $columnName): bool
    {
        if (!$this->header) {
            return false;
        }
        
        $this->header[] = $columnName;
        
        return true;
    }
    
    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }
    
    /**
     * @return mixed
     */
    public function currentLine()
    {
        return current($this->lines);
    }
    
    /**
     * @return false|mixed|string
     */
    public function currentAmount()
    {
        return current($this->lines)[$this->amountColumnNumber()] ?? false;
    }
    
    /**
     * @return void
     */
    public function nextLine(): void
    {
        next($this->lines);
    }
    
    /**
     * @return false|int|string
     */
    protected function amountColumnNumber()
    {
        if ($this->header) {
            $this->amountColumnNumber = array_search('RUB', $this->header);
        } else {
            $this->amountColumnNumber = static::AMOUNT_COLUMN_NUMBER;
        }
    
        return $this->amountColumnNumber;
    }
}