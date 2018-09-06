<?php
class Main {
        
        public $hashMap = array();
        
        // 辞書式順列
        private static function getPermutation($aryInput, $cnt) {
            
            $aryTemp = $aryInput;
            $nInput = count($aryInput);
         
            $div = $cnt-1;
            $aryMod = array();
            for ($i=1;$i<=$nInput;$i++) {
                $mod = $div % $i;
                $div = intdiv($div, $i);
                $aryMod[] = $mod;
            }
            $aryRev = array_reverse($aryMod);
            
            $result = "";
            for ($i=0;$i < count($aryRev);$i++) {
                $spliced = array_splice($aryTemp, $aryRev[ $i ], 1);
                $result .= $spliced[ 0 ];
            }
            
            return $result;
        }
        
        public function makeHash($base) {

            $aryInput = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );

            $ret = self::getPermutation($aryInput, $base);
            
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

