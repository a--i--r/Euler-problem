<?php
class Main {
        
        public function makeHash($base) {

           $ret = 0;
           $retArray = array();
           $upbound = ($base + 1) * pow(9, $base);
           
           for ($i=2;$i <= $upbound;$i++) {
               $sum = 0;
               $n = $i;
               while ($n > 0) {
                   $d = $n % 10;
                   $n /= 10;
                   $temp = $d;
                   for ($j=1;$j < $base;$j++) {
                       $temp *= $d;
                   }
                   $sum += $temp;
               }
               if ($sum == $i) {
                   $ret += $i;
                   $retArray[] = $i;
               }
           }

           $this->hashMap[ "${base}" ] = $ret;
           // ソート
           uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "驚くべきことに, 各桁を4乗した数の和が元の数と一致する数は3つしかない: \n\n";
            $strPRB = <<< PRB
1634 = 14 + 64 + 34 + 44
8208 = 84 + 24 + 04 + 84
9474 = 94 + 44 + 74 + 44
ただし, 1=14は含まないものとする. この数たちの和は 1634 + 8208 + 9474 = 19316 である.

各桁を5乗した数の和が元の数と一致するような数の総和を求めよ.
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
            $this->makeHash(5);

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

