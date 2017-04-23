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
 * for example: echo convertFrac([[1, 2], [1, 3], [1, 4]);  // result is (6,12)(4,12)(3,12)
 */

function convertFrac($lst)
{
    $simpleNums = [2, 3, 5, 7, 11];

    $findNextSimpleNum = function () use (&$simpleNums) {

        $verifiedNum = end($simpleNums) + 1;

        $finded = false;
        while (!$finded) {

            $finded = true;
            for ($i =2; $i < $verifiedNum; $i++) {
                if ($verifiedNum % $i == 0) {
                    $flag = false;
                    $verifiedNum += 1;
                    break;
                }
            }
        }

        $simpleNums[] = $verifiedNum;
    };

    // factorization
    $factorize = function ($num) use (&$simpleNums, $findNextSimpleNum) {
        
        $number = $num;
        $primeFactorsForNum = [];

        while ($number != 1) {
            
            $simpleNum = current($simpleNums);

            if ($number % $simpleNum == 0) {
                $number = $number / $simpleNum;
                $primeFactorsForNum[] = $simpleNum;

            } elseif (!is_int(next($simpleNums))) {
                end($simpleNums);
                $findNextSimpleNum();
                next($simpleNums);
            }

        }
        
        reset($simpleNums);
        return $primeFactorsForNum;
    };


    $result = "";

    if (sizeof($lst) == 0) {
        return $result;
    }

    $denominators = array_map(function ($ratNum) {
        return $ratNum[1];
    }, $lst);

    $maxDenom = max($denominators);

    $otherDenoms = array_reduce($denominators, function ($acc, $denom) use ($maxDenom) {
        if ($denom != $maxDenom) {
            $acc[] = $denom;
        }
        return $acc;
    }, []);

    $primeFactorsForMaxDenom = $factorize($maxDenom);

    $primeFactorsForOtherDenoms = array_map(function ($denom) use ($factorize) {
        return $factorize($denom);
    }, $otherDenoms);
    
    // check whether the maxDenom is the lowest common denominator
    $isCommonDenom = true;
    foreach ($otherDenoms as $denom) {
        if ($maxDenom % $denom != 0) {
            $isCommonDenom = false;
        }
    }


    if (!$isCommonDenom) {

        $numsOfPrimeFactorsForOtherDenoms = array_map(function ($primeFactorsForDenom) {

            $numOfPrimeFactorForDenom = array_reduce($primeFactorsForDenom, function ($acc, $item) {
                if (!array_key_exists($item, $acc)) {
                    $acc[$item] = 1;
                } else {
                    $acc[$item]++;
                }

                return $acc;
            }, []);

            return $numOfPrimeFactorForDenom;
        }, $primeFactorsForOtherDenoms);


        foreach ($numsOfPrimeFactorsForOtherDenoms as $numOfPrimeFactorsForOtherDenom) {

            $numOfPrimeFactorsForMaxDenom = array_reduce($primeFactorsForMaxDenom, function ($acc, $item) {
                if (!array_key_exists($item, $acc)) {
                    $acc[$item] = 1;
                } else {
                    $acc[$item]++;
                }
                return $acc;
            }, []);

            foreach ($numOfPrimeFactorsForOtherDenom as $primeFactor => $num) {

                if (array_key_exists($primeFactor, $numOfPrimeFactorsForMaxDenom)) {

                    $diff =  $num - $numOfPrimeFactorsForMaxDenom[$primeFactor];
                    if ($diff >0) {
                        for ($i = 0; $i < $diff; $i++) {
                            $primeFactorsForMaxDenom[] = $primeFactor;
                        }
                    }

                } else {
                    for ($i = 0; $i < $num; $i++) {
                        $primeFactorsForMaxDenom[] = $primeFactor;
                    }
                }
            }

        }

    }

    $commonDenom = array_reduce($primeFactorsForMaxDenom, function ($acc, $item) {
        $acc *= $item;
        return $acc;
    }, 1);

    // reduction of ordinary fractions to the common denominator
    $intermediateResult = array_reduce($lst, function ($acc, $ratNum) use ($commonDenom) {
        $addFactor = $commonDenom / $ratNum[1];
        $numerator = $addFactor * $ratNum[0];
        $denominator = $addFactor * $ratNum[1];
        $acc[] = [$numerator, $denominator];
        return $acc;
    }, []);

    $minNumerator = min(array_map(function ($ratNum) {
        return $ratNum[0];
    }, $intermediateResult));

    // check for contractility
    $divisor = 1;
    for ($i = 2; $i <= $minNumerator; $i++) {
        $isContractility = true;
        foreach ($intermediateResult as $ratNum) {
            if ($ratNum[0] % $i != 0 || $ratNum[1] % $i != 0) {
                $isContractility = false;
            }
        }
        if ($isContractility) {
            $divisor = $i;
        }
    }

    // fraction reduction using a divisor
    $result = array_reduce($intermediateResult, function ($acc, $ratNum) use ($divisor) {
        $numerator = $ratNum[0] / $divisor;
        $denominator = $ratNum[1] / $divisor;
        $acc .= "($numerator,$denominator)";
        return $acc;
    }, "");

    return $result;
}
