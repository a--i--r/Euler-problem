<?php
class Main {
    
        public $hashMap = array();
        
        public function makeHash($problem) {
            
            $maxLength = 0;
            $startNum = 0;
            
            // キャッシュ生成
            $cache = array_fill(0, $problem+1, -1);
            $cache[ 1 ] = 1;
            
            // チェック
            for ($i=2;$i <= $problem;$i++) {
                
                $seq = $i;
                $k = 0;
                while ($seq != 1 && $seq >= $i) {
                    $k++;
                    if ($seq % 2 === 0) {
                        $seq /= 2;
                    }
                    else {
                        $seq = $seq * 3 + 1;
                    }
                }
                $cache[ $i ] = $k + $cache[ $seq ];
                
                if ($cache[ $i ] > $maxLength) {
                    $maxLength = $cache[ $i ];
                    $startNum = $i;
                }
            }
            $this->hashMap[ $startNum ] = $maxLength;
            
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
n → n/2 (n が偶数)

n → 3n + 1 (n が奇数)

13からはじめるとこの数列は以下のようになる.

13 → 40 → 20 → 10 → 5 → 16 → 8 → 4 → 2 → 1
13から1まで10個の項になる. この数列はどのような数字からはじめても最終的には 1 になると考えられているが, まだそのことは証明されていない(コラッツ問題)

さて, 100万未満の数字の中でどの数字からはじめれば最長の数列を生成するか.

注意: 数列の途中で100万以上になってもよい
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
            $result = $key . " (" . $val . ")";
            
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

