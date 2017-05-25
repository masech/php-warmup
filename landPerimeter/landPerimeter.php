<?php

function landPerimeter($arr)
{
    $perimeter = 0;
    $arrLen = sizeof($arr);
    
    for ($i = 0; $i < $arrLen; $i++) {
        
        $lineLen = strlen($arr[$i]);
        for ($j = 0; $j < $lineLen; $j++) {
            
            if ($arr[$i][$j] == 'X') {
                $perimeter += 4;
                
                if (($j - 1) >= 0 && ($arr[$i][$j - 1] == 'X')) {
                    $perimeter = $perimeter - 2;
                }
                if (($i - 1) >= 0 && ($arr[$i - 1][$j]) == 'X') {
                    $perimeter = $perimeter - 2;
                }

            }
        }
    }
    
    return "Total land perimeter: $perimeter";
}
