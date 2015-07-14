<?php
class Main {
        
        private $sets = array();
    
        public function makeHash($base) {

           for ($i=2;$i <= $base;$i++) {
               for ($j=2;$j <= $base;$j++) {
                   $ret =  gmp_strval(gmp_mul($i, $j));
                   if (!in_array($ret, $this->sets)) {
                       $this->sets[] = $ret;
                   }
               }
           }
           
           $ret = count($this->sets);
            $this->hashMap[ "${base}" ] = $ret;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "2 ≤ a ≤ 5 と 2 ≤ b ≤ 5について, ab を全て考えてみよう: \n\n";
            $strPRB = <<< PRB
22=4, 23=8, 24=16, 25=32
32=9, 33=27, 34=81, 35=243
42=16, 43=64, 44=256, 45=1024
52=25, 53=125, 54=625, 55=3125
これらを小さい順に並べ, 同じ数を除いたとすると, 15個の項を得る:

4, 8, 9, 16, 25, 27, 32, 64, 81, 125, 243, 256, 625, 1024, 3125

2 ≤ a ≤ 100, 2 ≤ b ≤ 100 で同じことをしたときいくつの異なる項が存在するか?
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
            $this->makeHash(100);

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

