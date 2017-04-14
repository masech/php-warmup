<?php

/* Build Tower
 *
 * Build Tower by the following given argument:
 * number of floors (integer and always greater than 0)
 * 
 * Tower block is represented as *
 *
 * for example: print_r(towerBuilder(6))
 */

function towerBuilder($n)
{
    $floorNum = $n;
    $starsMaxNum = ($floorNum * 2) - 1;
    $tower = [];
    $floor = '';
    
    for ($i = 1; $i <= $floorNum; $i++) {
        $starsNumAtFloor = ($i * 2) - 1;
        $diff = $starsMaxNum - $starsNumAtFloor;
        
        $edge = '';
        for ($j = 0; $j < ($diff / 2); $j++) {
            $edge .= ' ';
        }
        
        $middle = '';
        for ($k = 0; $k < $starsNumAtFloor; $k++) {
            $middle .= '*';
        }
        
        $floor = $edge . $middle . $edge;
        $tower[] = $floor;
    }
    
    return $tower;
}
