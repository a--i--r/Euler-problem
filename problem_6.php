<?php
    class Main {

        /**
         * Project euler problem 6
         */
        public function execute($args=null)
        {
            $strProblem = "最初の100個の自然数について二乗の和と和の二乗の差を求めよ.";
            $result = "1";
            $squareSum = "0";
            $sumSquare = "1";
            $sum = "0";
            for ($i=1;$i <= 100;$i++) {
                $squareSum = gmp_strval(gmp_add($squareSum, $i*$i));
                $sum = gmp_strval(gmp_add($sum, $i));
            }
            $sumSquare = gmp_strval(gmp_pow($sum, 2));
            $result = gmp_strval(gmp_abs(gmp_sub($squareSum, $sumSquare)));
            return  $result;
        }
        
    }
    $main = new Main();
    echo "result: " . $main->execute();
?>