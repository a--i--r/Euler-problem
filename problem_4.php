<?php
    class Main {

    /**
     * Project euler problem 4
     */
    public function main($args=null)
    {
        $strProblem = "左右どちらから読んでも同じ値になる数を回文数という. 2桁の数の積で表される回文数のうち, 最大のものは 9009 = 91 × 99 である.\n";
        $strProblem .= "では, 3桁の数の積で表される回文数の最大値を求めよ.";
        $result = "";
        $x = $y = 0;
        for ($i=100;$i < 1000;$i++) {
            for ($j=100;$j < 1000;$j++) {
                $mul = $i * $j;
                if ($mul == strrev($mul)) {
                    if ($mul > $result) {
                        $result = $mul;
                        $x = $i;
                        $y = $j;
                    }
                }
            }
        }
        return $x . " * " . $y . " = " . $result;
    }
        
    }
    $main = new Main();
    echo "result: " . $main->main();
?>