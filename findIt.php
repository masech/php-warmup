<?php

/*
 * Given an array, find the int that appears an odd number of times.
 * There will always be only one integer that appears an odd number of times.
 * 
 * for example: echo findIt([1,1,2,-2,5,2,4,4,-1,-2,5]); // result is -1
 */

function findIt(array $seq) : int
{
    //$map is [ $seq[i] => numOfRepeat_$seq[i] ]
    $map = array_reduce($seq, function ($acc, $item) {
        if (!array_key_exists($item, $acc)) {
            $acc[$item] = 1;
        } else {
            $acc[$item]++;
        }

        return $acc;
    }, []);

    $result = 0;
    foreach ($map as $key => $value) {
        if ($value % 2 != 0) {
            $result = $key;
        }
    }

    return $result;
}
