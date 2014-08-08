<?php
class Main {
    
        public $hashMap = array();
        
        public function makeHash($size) {

            // キャッシュ生成
            $cache = array_fill(0, $size, 1);
            
            // チェック
            for ($i=1;$i <= $size;$i++) {
                for ($j=1;$j < $i;$j++) {
                    $cache[ $j ] = $cache[ $j ] + $cache[ $j - 1 ];
                }
                $cache[ $i ] = 2 * $cache[ $i - 1 ];
            }
            
            $this->hashMap[ $size ] = $cache[ $size ];
            
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "2×2 のマス目の左上からスタートした場合, 引き返しなしで右下にいくルートは 6 つある.\n\n";
            $strPRB = <<< PRB
では, 20×20 のマス目ではいくつのルートがあるか.
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
            $this->makeHash(20);

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

