<?php
    class Main {

    /**
     * Project euler problem 2
     */
    public function main($args=null)
    {
        $strProblem = "フィボナッチ数列の項は前の2つの項の和である。最初の2項を 1, 2 とすれば、";
        $strProblem .= "数列の項の値が400万を超えない範囲で、偶数値の項の総和を求めよ。";
        $result = $sum = 0;
        $first = 1;
        $next = $evenSum = 2;

        while ( $sum < 4000000 ) {
            $sum = $first + $next;
            $first = $next;
            $next = $sum;
            if ($next % 2 === 0) {
                $evenSum += $next;
            }
        }
        $result = $evenSum;
        return $result;
    }
        
    }
    $main = new Main();
    echo "result: " . $main->main();
?>