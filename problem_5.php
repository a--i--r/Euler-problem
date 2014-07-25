<?php
    class Main {

    /**
     * Project euler problem 5
     */
    public static function lcm($a, $b) {
        return gmp_strval(gmp_div(gmp_mul($a, $b), gmp_gcd($a, $b)));
    }
    public function execute($args=null)
    {
        $strProblem = "2520は1から10までのどの整数でも余りなく割り切れる最小の数である。\n";
        $strProblem .= "1から20までの全ての数で割り切れる最小の正の整数は何か?";
        $result = "1";
        for ($i=2;$i <= 20;$i++) {
            $result = self::lcm($result, $i);
        }
        return  $result;
    }
        
    }
    $main = new Main();
    echo "result: " . $main->execute();
?>