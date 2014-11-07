<?php
class Main {
        
        public $hashMap = array();
        private $aryAbundant = array();
        private $aryAbundantCache = array();

        private function sumDividers($n) {
            $aryDividers[] = 1;
            
            list($max, $rem) = gmp_sqrtrem($n);
            $max = gmp_intval($max);
            if ($max * $max !== $n) {
                $max += 1;
            }
            else {
                $aryDividers[] = $max;
            }
            for ($i=2;$i <= $max;$i++) {
                if (gmp_cmp(gmp_mod($n, $i), 0) == 0) {
                    $aryDividers[] = $i;
                    if ($i < ($n / $i)) {
                        $aryDividers[] = ($n / $i);
                    }
                }
            }
            $aryDividers = array_unique($aryDividers);
            return array_sum($aryDividers);
        }
        
        private function prepareAbundantCache($base) {
            
            $this->aryAbundantCache = array_fill(0, $base+1, FALSE);
            for ($i=0;$i < count($this->aryAbundant);$i++) {
                $val = $this->aryAbundant[ $i ];
                $this->aryAbundantCache[ $val ] = TRUE;
            }
        }
        
        private function isAbundant($n) {
            
            if ($n < 12) { return FALSE; }
            $sum = $this->sumDividers($n);
            return $sum > $n ? TRUE : FALSE;
        }
        
        private function isAbundantSum($n) {
            
            if (!is_array($this->aryAbundant)) { return FALSE; }
            if ($n < 24) { return FALSE; }
            for($i=0;$i < count($this->aryAbundant);$i++) {
                $val = $this->aryAbundant[ $i ];
                if ($val > $n) { return FALSE; }
                if ($this->aryAbundantCache[ $n - $val ]) {
                    return TRUE;
                }
            }
            return FALSE;
        }
        
        public function makeHash($base) {

            $this->aryAbundant = array();
            for ($i=12;$i <= $base;$i++) {
                if ($this->isAbundant($i)) {
                    $this->aryAbundant[] = $i;
                }
            }
            $this->prepareAbundantCache($base);
            $sum = 0;
            for ($i=1;$i < $base+1;$i++) {
                if (!$this->isAbundantSum($i)) {
                    $sum += $i;
                }
            }
            
            $this->hashMap[ "${base}" ] = $sum;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "完全数とは, その数の真の約数の和がそれ自身と一致する数のことである.\n\n";
            $strPRB = <<< PRB
たとえば, 28の真の約数の和は, 1 + 2 + 4 + 7 + 14 = 28 であるので, 28は完全数である.

真の約数の和がその数よりも少ないものを不足数といい, 真の約数の和がその数よりも大きいものを過剰数と呼ぶ.

12は, 1 + 2 + 3 + 4 + 6 = 16 となるので, 最小の過剰数である. よって2つの過剰数の和で書ける最少の数は24である. 
数学的な解析により, 28123より大きい任意の整数は2つの過剰数の和で書けることが知られている. 
2つの過剰数の和で表せない最大の数がこの上限よりも小さいことは分かっているのだが, この上限を減らすことが出来ていない.

2つの過剰数の和で書き表せない正の整数の総和を求めよ.
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
            $this->makeHash(28123);

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

