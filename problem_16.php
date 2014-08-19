<?php
class Main {
        
        const BASE_NUM = 2;
    
        public $hashMap = array();
        
        public function makeHash($pow) {

            $powered = gmp_strval(gmp_pow(Main::BASE_NUM, $pow));
            $aryPowered = str_split($powered);
            $sum = "0";
            for ($i=0;$i < count($aryPowered);$i++) {
                $sum = gmp_strval(gmp_add($sum, $aryPowered[ $i ]));
            }
            $this->hashMap[ $powered ] = $sum;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "215 = 32768 であり, これの数字和 ( 各桁の和 ) は 3 + 2 + 7 + 6 + 8 = 26 となる.\n\n";
            $strPRB = <<< PRB
同様にして, 2の1000乗 の数字和を求めよ.
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

