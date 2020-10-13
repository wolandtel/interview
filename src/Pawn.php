<?php

class Pawn extends Figure {
    public function __toString() {
        return $this->isBlack ? '♟' : '♙';
    }
    
    public function check(Move $current, ?Move $prev): void {
        if ($this->isBlack && $current->getYFrom() <= $current->getYTo() ||
            !$this->isBlack && $current->getYFrom() >= $current->getYTo())
        {
            throw new Exception("Pawn doesn't go backward or move horizontally");
        }
        
        if ($current->getXFrom() == $current->getXTo()) {
            if ($this->isBlack) {
                $doubleAllowed = 7;
                $offset = -1;
            } else {
                $doubleAllowed = 2;
                $offset = 1;
            }
            if ($current->getYFrom() == $doubleAllowed) {
                $maxDiff = 2;
            } else {
                $maxDiff = 1;
            }
            if (abs($current->getYFrom() - $current->getYTo()) > $maxDiff) {
                throw new Exception("Pawn can't jump");
            }
            if ((abs($current->getYFrom() - $current->getYTo()) == 2) &&
                isset($current->getState()[$current->getXFrom()][$current->getYFrom() + $offset]))
            {
                throw new Exception("Pawn can't fly");
            }
        } else {
            if (abs(ord($current->getXFrom()) - ord($current->getXTo())) > 1) {
                throw new Exception("Pawn strafes too fast");
            }
            if (abs($current->getYFrom() - $current->getYTo()) > 1) {
                throw new Exception("Pawn can't jump");
            }
            if (!$figure = ($current->getState()[$current->getXTo()][$current->getYTo()] ?? null)) {
                throw new Exception("Couldn't beat the void");
            }
            if ($figure->isBlack() == $this->isBlack) {
                throw new Exception('Friendly fire');
            }
        }
    }
}
