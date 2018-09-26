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
    
    public function getSolution($n) {
        
        $ret = 0;
        $aryPrimes = self::doSeive($n);
        $arySum = array();
        $nPrimes = count($aryPrimes);
        $cntPrimes = 0;
        
        $arySum[ 0 ] = 0;
        for ($i=0;$i < $nPrimes;$i++) {
            $arySum[ $i + 1 ] = $arySum[ $i ] + $aryPrimes[ $i ]; 
        }
        $nSum = count($arySum);
        
        for ($i=$cntPrimes;$i < $nSum;$i++) {
            for ($j = $i - ($cntPrimes+1);$j >= 0;$j--) {
                if ($arySum[ $i ] - $arySum[ $j ] > $n) break;
                
                $num = $arySum[ $i ] - $arySum[ $j ];
                if (in_array($num, $aryPrimes)) {
                    $cntPrimes = $i - $j;
                    $ret = $num;
                }
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
        
        $this->strProblem = "素数41は6つの連続する素数の和として表せる:\n\n";
        $strPRB = <<< PRB
41 = 2 + 3 + 5 + 7 + 11 + 13.
            
100未満の素数を連続する素数の和で表したときにこれが最長になる.
同様に, 連続する素数の和で1000未満の素数を表したときに最長になるのは953で21項を持つ.

100万未満の素数を連続する素数の和で表したときに最長になるのはどの素数か?
PRB;

        $this->strProblem .= $strPRB;
        $arg = "1000000";

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
