<?php
class Main {
        
        public $hashMap = array();
        
        private function fact($n) {
            if ($n === "0") { return "1"; }
            return gmp_strval(gmp_mul($n, $this->fact(gmp_strval(gmp_sub($n, 1)))));
        }
        
        public function makeHash($base) {

            $sum = 0;
            $fact = $this->fact($base);
            for($i=0;$i < strlen($fact);$i++) {
                $sum += intval($fact[ $i ]);
            }
            
            $this->hashMap[ "${base}:${fact}" ] = $sum;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "n × (n - 1) × ... × 3 × 2 × 1 を n! と表す.\n\n";
            $strPRB = <<< PRB
例えば, 10! = 10 × 9 × ... × 3 × 2 × 1 = 3628800 となる.
この数の各桁の合計は 3 + 6 + 2 + 8 + 8 + 0 + 0 = 27 である.

では, 100! の各桁の数字の和を求めよ.
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
            $this->makeHash("100");

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

