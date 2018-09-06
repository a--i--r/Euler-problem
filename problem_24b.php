<?php
class Main {
        
        public $hashMap = array();
        
        // 再帰
        private static function getPermutation($aryInput, $cnt) {
            
            $nPerm = count($aryInput);
            if ($nPerm <= 0) {
                return array();
            }
            
            $aryOutput = array();
            for ($i=0;$i < $nPerm;$i++) {
                $arySliced = array_slice($aryInput, $i, 1);
                $aryRest = array_merge( array_slice($aryInput, 0, $i), array_slice($aryInput, $i+1) );
                $aryNum = self::getPermutation($aryRest, $cnt);
                $nNum = count($aryNum);
                if ($nNum <= 0) {
                    $aryOutput[] = $arySliced[0];
                }
                else {
                    
                    for ($j=0;$j < $nNum;$j++) {
                        $aryOutput[] = $arySliced[0] . $aryNum[$j];
                        if (count($aryOutput) == $cnt) {
                            break 2;
                        }
                    }
                    
                }

            }
            return $aryOutput;
        }
        
        public function makeHash($base) {

            $aryInput = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );

            $ret = self::getPermutation($aryInput, $base);
            
            $this->hashMap[ "${base}" ] = $ret[ $base - 1 ];
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

