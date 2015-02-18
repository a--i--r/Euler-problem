<?php
class Main {
        
        public $hashMap = array();
        public $primes = array();
        public $compos = array();
 
        private function initPrimes($base) {
            
            $aryP = array_fill(0, $base+1, TRUE);
            $aryP[ 0 ] = FALSE;
            $aryP[ 1 ] = FALSE;
            
            for ($i=2;$i < count($aryP);$i++) {
                $val = $aryP[ $i ];
                if ($val === TRUE) {
                    $j = $i * 2;
                    while ( $j < count($aryP) ) {
                        $aryP[ $j ] = FALSE;
                        $j += $i;
                    }
                }
            }
            return $aryP;
        }
        
        public function makeHash($base) {

            $this->primes = $this->initPrimes($base*$base);
            $aryReturn = array(0,0,0);
            
            $a = -$base;
            while ($a < $base+1) {
                $b = 2;
                while ($b < $base+1) {
                    if ($this->primes[ $b ] === FALSE) {
                        $b += 1;
                        continue;
                    }
                    $t = -1600 - 40 * $a;
                    if ($b < $t || $b < $aryReturn[ 2 ]) {
                        $b += 1;
                        continue;
                    }
                    $c = 0;
                    $n = 0;
                    $val = pow($n, 2) + $a * $n + $b;
                    if ($val < 0) { $val = abs($val); }
                    while ($this->primes[ $val ] === TRUE) {
                        $c += 1;
                        $n += 1;
                        $val = pow($n, 2) + $a * $n + $b;
                        if ($val < 0) { $val = abs($val); }
                    }
                    if ($c > $aryReturn[ 2 ]) {
                        $aryReturn = array($a, $b, $c);
                    }
                    $b += 1;
                }
                $a += 1;
            }
            var_dump($aryReturn);
            
            $ret = $aryReturn[ 0 ] * $aryReturn[ 1 ];
            
            $this->hashMap[ "${base}" ] = $ret;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "オイラーは以下の二次式を考案している: \n\n";
            $strPRB = <<< PRB
n2 + n + 41.
この式は, n を0から39までの連続する整数としたときに40個の素数を生成する. 
しかし, n = 40 のとき 402 + 40 + 41 = 40(40 + 1) + 41 となり41で割り切れる. また, n = 41 のときは 412 + 41 + 41 であり明らかに41で割り切れる.

計算機を用いて, 二次式 n2 - 79n + 1601 という式が発見できた. これは n = 0 から 79 の連続する整数で80個の素数を生成する. 係数の積は, -79 × 1601 で -126479である.

さて, |a| < 1000, |b| < 1000 として以下の二次式を考える (ここで |a| は絶対値): 例えば |11| = 11 |-4| = 4である.

n2 + an + b
n = 0 から始めて連続する整数で素数を生成したときに最長の長さとなる上の二次式の, 係数 a, b の積を答えよ.
PRB;
            
            $this->strProblem .= $strPRB;
            $arg = "";
            
            $result = "";
            if (is_array($args) && array_key_exists(0, $args)) {
                list($arg) = $args;
            }
            
            $this->strProblem .= $arg . "\n";

            if (strlen($this->strProblem) > 0) {
                echo $this->strProblem;
            }
            
            // 計算
            $this->makeHash(1000);

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
?>

