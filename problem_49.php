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
    
    private static function isPermutation($m, $n) {
        
        $aryNum = array_fill(0, 10, 0);
        $temp = $n;
        
        while ($temp > 0) {
            $aryNum[ $temp % 10 ]++;
            $temp /= 10;
        }
        
        $temp = $m;
        
        while ($temp > 0) {
            $aryNum[ $temp % 10 ]--;
            $temp /= 10;
        }
        
        for ($i=0;$i < 10;$i++) {
            if ($aryNum[ $i ] != 0) {
                return false;
            }
        }
        return true;
    }
    
    public function getSolution($n) {
        
        $ret = 0;
        $aryPrimes = self::doSeive($n);
        $nPrimes = count($aryPrimes);
        
        for ($i=0;$i < $nPrimes;$i++) {
            if ($aryPrimes[ $i ] <= 1489) { continue; }
            
            for ($j=$i+1;$j < $nPrimes;$j++) {
                $k = $aryPrimes[ $j ] + ($aryPrimes[ $j ] - $aryPrimes[ $i ]);
                if ($k < $n && in_array($k, $aryPrimes)) {
                    if (self::isPermutation($aryPrimes[ $i ], $aryPrimes[ $j ]) &&
                        self::isPermutation($aryPrimes[ $j ], $k)) {
                        $ret = $aryPrimes[ $i ] . $aryPrimes[ $j ] . $k;
                        break;
                    }
                }
            }
            if ($ret > 0) {
                break;
            }
        }
        return $ret;
    }
   
    public function makeHash($arg) {

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
        
        $this->strProblem = "項差3330の等差数列1487, 4817, 8147は次の2つの変わった性質を持つ.\n\n";
        $strPRB = <<< PRB
            
(i)3つの項はそれぞれ素数である. 
(ii)各項は他の項の置換で表される. 
            
1, 2, 3桁の素数にはこのような性質を持った数列は存在しないが, 4桁の増加列にはもう1つ存在する.

それではこの数列の3つの項を連結した12桁の数を求めよ.
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
