<?php

/* 
 * Find the missing term in an Arithmetic Progression
 *
 * You are provided with consecutive elements of an Arithmetic Progression. There is however one hitch: Exactly
 * one term from the original series is missing from the set of numbers which have been given to you. The rest
 * of the given series is the same as the original AP. Find the missing term. 
 * 
 * You have to write the function findMissing(list) , list will always be atleast 3 numbers. The missing term 
 * will never be the first or last one.
 * 
 * for example: echo findMissing([1, 3, 5, 9, 11]);  // result is 7 
 */

function findMissing($list)
{
    $step = ($list[1] - $list[0]) < ($list[2] - $list[1]) ? $list[1] - $list[0] : $list[2] - $list[1];

    $sumThatIs = array_sum($list);

    $sumThatMustBe = array_sum(range($list[0], end($list), $step));

    return $sumThatMustBe - $sumThatIs;
}
