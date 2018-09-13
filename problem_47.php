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
    
    private static function getNumberOfPrimeFactors($n, $aryPrimes) {
        
        $ret = 0;
        $bPrimeFactor = false;
        $remain = $n;
        
        for ($i=0;$i < count($aryPrimes);$i++) {
            if ($aryPrimes[ $i ] * $aryPrimes[ $i ] > $n) {
                return ++$ret;
            }
            
            $bPrimeFactor = false;
            while ($remain % $aryPrimes[ $i ] == 0) {
                $bPrimeFactor = true;
                $remain = $remain / $aryPrimes[ $i ];
            }
            if ($bPrimeFactor) {
                $ret++;
            }
            if ($remain == 1) {
                return $ret;
            }
        }
        return $ret;
    }
    
    public function getSolution($n) {
        
        $aryPrimes = self::doSeive($n);
        $targetPf = 4;
        $targetConsec = 4;
        $consec = 1;
        $ret = 2 * 3 * 5 * 7;
        
        while ($consec < $targetConsec) {
            $ret++;
            if (self::getNumberOfPrimeFactors($ret, $aryPrimes) >= $targetPf) {
                $consec++;
            }
            else {
                $consec = 0;
            }
        }
        return $ret - $targetConsec + 1;
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
        
        $this->strProblem = "それぞれ2つの異なる素因数を持つ連続する2つの数が最初に現れるのは:\n\n";
        $strPRB = <<< PRB
            
14 = 2 × 7
15 = 3 × 5

それぞれ3つの異なる素因数を持つ連続する3つの数が最初に現れるのは:

644 = 22 × 7 × 23
645 = 3 × 5 × 43
646 = 2 × 17 × 19

最初に現れるそれぞれ4つの異なる素因数を持つ連続する4つの数を求めよ. その最初の数はいくつか?
PRB;

        $this->strProblem .= $strPRB;
        $arg = "100000";

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
