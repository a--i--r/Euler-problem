<?php
class Main {
        
        public $hashMap = array();
        
        private function sumDividers($n) {
            $sum = 0; $i = 1;
            //$n = intval($n / 2);
            $m = ceil(($n+1) / 2);
            while ($i < $m) {
                if ($n % $i === 0) {
                    $sum += $i;
                }
                $i++;
            }
            return $sum;
        }
        
        private function sumAmicablePairs($low, $high) {
            $a = $low;
            $b = $sum = 0;
            while ($a <= $high) {
                $b = $this->sumDividers($a);
                $sumB = $this->sumDividers($b);
                if ($b > $a && $a === $sumB) {
                    $sum += $a + $b;
                }
                $a++;
            }
            return $sum;
        }
        
        public function makeHash($base) {

            $sum = $this->sumAmicablePairs(1, $base);
            
            $this->hashMap[ "${base}" ] = $sum;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "d(n) を n の真の約数の和と定義する. (真の約数とは n 以外の約数のことである. )\n\n";
            $strPRB = <<< PRB
もし, d(a) = b かつ d(b) = a (a ≠ b のとき) を満たすとき, a と b は友愛数(親和数)であるという.

例えば, 220 の約数は 1, 2, 4, 5, 10, 11, 20, 22, 44, 55, 110 なので d(220) = 284 である.
また, 284 の約数は 1, 2, 4, 71, 142 なので d(284) = 220 である.

それでは10000未満の友愛数の和を求めよ.
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
            $this->makeHash("10000");

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

