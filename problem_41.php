<?php
ini_set('memory_limit', '2048M');

class Main {

    protected $aryPrimes;
    
    public function __construct() {

        $this->aryPrimes = array();
    }
    
    public function isPrime($input) {
        return in_array($input, $this->aryPrimes);
    }
    
    public function doSeive($limit) {

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
    
    private function isPandigital($target) {
        
        $aryTarget = str_split(strval($target));
        $length = strlen($target);
        sort($aryTarget, SORT_NUMERIC);
        $strTargetSorted = implode("",$aryTarget);
        if (substr("123456789", 0, $length) == $strTargetSorted) {
            return true;
        }
        return false;
    }
    
    public function getSolution($n) {
        
        $ret = 1;
        $this->aryPrimes = $this->doSeive($n);
        $seivecount = count($this->aryPrimes) - 1;

        for ($i=$seivecount;$i >= 0;$i--) {
            //echo "Prime: " . $this->aryPrimes[$i] . "\n";
            if ($this->isPandigital($this->aryPrimes[ $i ])) {
                $ret = $this->aryPrimes[ $i ];
                break;
            }
        }
        return $ret;
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
        
        $this->strProblem = "n桁パンデジタルであるとは, 1からnまでの数を各桁に1つずつ持つこととする.\n\n";
        $strPRB = <<< PRB
            
例えば2143は4桁パンデジタル数であり, かつ素数である. 
    
n桁（この問題の定義では9桁以下）パンデジタルな素数の中で最大の数を答えよ.
PRB;

        $this->strProblem .= $strPRB;
        $arg = "7654321";

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
