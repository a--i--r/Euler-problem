<?php
class Main {
        
        public $hashMap = array();
 
        private function getPermutation($n) {
            
            if ( $n < 0) {
                return 0;
            }
            $p = 1;
            for ($i=1;$i <= $n;$i++) {
                $p *= $i;
            }
            return $p;
        }
        
        public function makeHash($base) {

            $aryPerm = $aryNum = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );

            $ret = "";
            $remain = $base - 1;
            $n = count($aryNum);
            for ($i=1;$i < $n;$i++) {
                $p = $this->getPermutation($n - $i);
                $fl = floor($remain / $p);
                $remain = $remain % $p;
                $ret .= $aryNum[ $fl ];
                array_splice($aryNum, $fl, 1);
                if ($remain == 0) {
                    break;
                }
            }
            for ($i=0;$i < count($aryNum);$i++) {
                $ret .= $aryNum[ $i ];
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
            $this->strProblem = "順列とはモノの順番付きの並びのことである. たとえば, 3124は数 1, 2, 3, 4 の一つの順列である. \n\n";
            $strPRB = <<< PRB
すべての順列を数の大小でまたは辞書式に並べたものを辞書順と呼ぶ. 0と1と2の順列を辞書順に並べると

012 021 102 120 201 210
になる.

0,1,2,3,4,5,6,7,8,9からなる順列を辞書式に並べたときの100万番目はいくつか?
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
            $this->makeHash(1000000);

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

