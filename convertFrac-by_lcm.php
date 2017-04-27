<?php

/*
 * Common denominators
 *
 * You will have a list of rationals in the form: [ [numer_1, denom_1] , ... [numer_n, denom_n] ]
 * where all numbers are positive ints.
 * 
 * You have to produce a result in the form: (N_1', D) , ... (N_n, D)
 * in which D is as small as possible and  N_1/D == numer_1/denom_1 ... N_n/D == numer_n,/denom_n.
 * 
 * for example: echo convertFrac([[1, 2], [1, 3], [1, 4]]);  // result is (6,12)(4,12)(3,12)
 */

function convertFrac($lst)
{
    $gcd = function ($a, $b) {
        while ($a != 0 && $b != 0) {
            if ($a > $b) {
                $a = $a % $b;
            } else {
                $b = $b % $a;
            }
        }
        return ($a + $b);
    };

    $lcm = function ($a, $b) use ($gcd) {
        return ($a * $b) / $gcd($a, $b);
    };
    
    
    $result = "";
    
    if (empty($lst)) {
        return $result;
    }

    $commonDenom = array_reduce($lst, function ($acc, $rat) use ($lcm) {
        if ($rat[1] != $acc) {
            $acc = $lcm($acc, $rat[1]);
        }
        return $acc;
    }, $lst[0][1]);
    
    $preResult = array_reduce($lst, function ($acc, $rat) use ($commonDenom) {
        $addFactor = $commonDenom / $rat[1];
        $numerator = $addFactor * $rat[0];
        $denominator = $addFactor * $rat[1];
        $acc[] = [$numerator, $denominator];
        return $acc;
    }, []);
    
    $minNumerator = min(array_map(function ($rat) {
        return $rat[0];
    }, $preResult));
    
    // check for contractility
    $divisor = 1;
    for ($i = 2; $i <= $minNumerator; $i++) {
        $isContractility = true;
        foreach ($preResult as $rat) {
            if ($rat[0] % $i != 0 || $rat[1] % $i != 0) {
                $isContractility = false;
            }
        }
        if ($isContractility) {
            $divisor = $i;
        }
    }
    
    $result = array_reduce($preResult, function ($acc, $rat) use ($divisor) {
        $numerator = $rat[0] / $divisor;
        $denominator = $rat[1] / $divisor;
        $acc .= "($numerator,$denominator)";
        return $acc;
    });
    
    return $result;
}
