<?php

class Move {
    private $xFrom;
    private $yFrom;
    private $xTo;
    private $yTo;
    /** @var Figure */
    private $figure;
    private $state;
    
    public function __construct(string $move) {
        if (!preg_match('/^([a-h])([1-8])-([a-h])([1-8])$/', $move, $match)) {
            throw new \Exception("Incorrect move");
        }
        
        $this->xFrom = $match[1];
        $this->yFrom = (int)$match[2];
        $this->xTo   = $match[3];
        $this->yTo   = (int)$match[4];
    }
    
    public function apply(array &$figures, ?Move $prev): void
    {
        if (!isset($figures[$this->xFrom][$this->yFrom])) {
            throw new \Exception('Empty field');
        }
        
        $this->figure = $figures[$this->xFrom][$this->yFrom];
        $this->saveState($figures);
        $this->check($prev);
        
        $figures[$this->xTo][$this->yTo] = $this->figure;
        
        unset($figures[$this->xFrom][$this->yFrom]);
    }
    
    public function getFigure(): Figure
    {
        return $this->figure;
    }
    
    private function check(?Move $prev): void
    {
        if ($prev && ($prev->getFigure()->isBlack() == $this->figure->isBlack())) {
            throw new Exception('Should be different colors');
        }
        
        $this->figure->check($this, $prev);
    }
    
    public function getXFrom(): string
    {
        return $this->xFrom;
    }
    
    public function getYFrom(): int
    {
        return $this->yFrom;
    }
    
    public function getXTo(): string
    {
        return $this->xTo;
    }
    
    public function getYTo(): int
    {
        return $this->yTo;
    }
    
    private function saveState(array $figures): void
    {
        foreach (['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'] as $x) {
            for ($y = 1; $y <= 8; $y++) {
                $this->state[$x][$y] = $figures[$x][$y] ?? null;
            }
        }
    }
    
    public function getState(): array
    {
        return $this->state;
    }
}