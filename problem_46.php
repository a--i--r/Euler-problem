<?php
ini_set('memory_limit', '2048M');

class Main {

    public      $hashMap = array();
    
    public function __construct() {

    }
    
    public static function doSeive($limit) {

        $sieveBound = gmp_strval(gmp_div(gmp_sub($limit, 1), 2));
        $upperSqrt = gmp_strval(gmp_div(gmp_sub(gmp_sqrt($limit), 1), 2));

        $bitArray = array_fill(0, $sieveBound+1, 1);
        for ($i="1";gmp_cmp($i, $upperSqrt) <= 0;$i = gmp_add($i, 1)) {
            for($j=gmp_mul(gmp_mul($i, 2),gmp_add($i, 1));gmp_cmp($j, $sieveBound) <= 0;$j = gmp_add($j, gmp_add(gmp_mul($i, 2),1))) {
                $t = gmp_intval($j);
                $bitArray[ $t ] = 0;
            }
        }
        $numbers = array("2");
        for ($i="1";gmp_cmp($i, $sieveBound) <= 0;$i = gmp_add($i, 1)) {
            if ($bitArray[ gmp_intval($i) ]) {
                $numbers[] = gmp_strval(gmp_add(gmp_mul(2, $i), 1));
            }
        }
        return $numbers;
    }
    
    private static function isTwiceSquare($n) {
        
        list($div, $q) = gmp_div_qr(gmp_init($n), 2);
        if (gmp_strval($q) != "0") { return false; }
        
        list($sqrted, $q) = gmp_sqrtrem($div);
        if (gmp_strval($q) != "0") { return false; }
        return true;
    }
    
    public function getSolution($n) {
        
        
        $aryPrimes = self::doSeive($n);
        $notFound = true;
        $i = 1;
        
        while ($notFound) {
            $i += 2;
            
            $j = 0;
            $notFound = false;
            while ($i >= $aryPrimes[ $j ]) {
                $num = $i - $aryPrimes[ $j ];
                if (self::isTwiceSquare($num)) {
                    $notFound = true;
                    break;
                }
                $j++;
            }
            
        }
        return $i;
    }
   
    public function makeHash($arg) {

        $ret = 0;
        
        $ret = $this->getSolution($arg);
        
        $base = implode(", ", [$arg]);
        $this->hashMap[ "${base}" ] = $ret;
        
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null) {
        
        $this->strProblem = "Christian Goldbachは全ての奇合成数は平方数の2倍と素数の和で表せると予想した.\n\n";
        $strPRB = <<< PRB
            
9 = 7 + 2×12
15 = 7 + 2×22
21 = 3 + 2×32
25 = 7 + 2×32
27 = 19 + 2×22
33 = 31 + 2×12

後に, この予想は誤りであることが分かった.

平方数の2倍と素数の和で表せない最小の奇合成数はいくつか?
PRB;

        $this->strProblem .= $strPRB;
        $arg = "10000";

        $result = "";
        if (is_array($args) && array_key_exists(0, $args)) {
            list($arg) = $args;
        }

        $this->strProblem .= "[ $arg ]\n";

        if (strlen($this->strProblem) > 0) {
            echo $this->strProblem;
        }

        // 計算
        $this->makeHash($arg);

        // 取り出し
        if (count($this->hashMap) <= 0) { return "Cannot get a return value.\n"; }
        list($val, $key) = array(end($this->hashMap), key($this->hashMap));

        // 結果
        $result = $val . " (" . $key . ")";

        return $result;
    }

}

$time_start = microtime(true);
$main = new Main();

echo "\nresult: " . $main->execute() . "\n";

$time_end = microtime(true);
$time = $time_end - $time_start;
echo "time elapsed: " . $time . " sec.";
