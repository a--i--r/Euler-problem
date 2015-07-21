<?php
class Main {
        
        private $coins = array(1, 2, 5, 10, 20, 50, 100, 200);
        private $memos = array();
    
        private function rec($n, $step) {
            
            if ($step == sizeof($this->coins)) return 1;
            $cur = $n;
            $ret = 0;
            while ($cur >= 0) {
                $memo = -1; 
                if (array_key_exists($cur, $this->memos)) {
                    if (is_array($this->memos[ $cur ]) && array_key_exists($step+1, $this->memos[ $cur ])) {
                        $memo = $this->memos[ $cur ][ $step+1 ];
                    }
                }
                if ($memo == -1) {
                    $memo = $this->rec($cur, $step+1);
                    if (!is_array($this->memos[ $cur ])) { $this->memos[ $cur ] = array(); }
                    $this->memos[ $cur ][ $step+1 ] = $memo;
                }
                $ret += $memo;
                $cur -=  $this->coins[ $step ];
            }
            
            return $ret;
        }
        
        public function makeHash($base) {

           $this->memos = array_fill(0, $this->coins[sizeof($this->coins) - 1] + 1, -1);
           // 再帰
           $ret = $this->rec($base,1);

           $this->hashMap[ "${base}" ] = $ret;
           // ソート
           uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "イギリスでは硬貨はポンド£とペンスpがあり，一般的に流通している硬貨は以下の8種類である.: \n\n";
            $strPRB = <<< PRB
1p, 2p, 5p, 10p, 20p, 50p, £1 (100p) and £2 (200p).

以下の方法で£2を作ることが可能である．

1×£1 + 1×50p + 2×20p + 1×5p + 1×2p + 3×1p

これらの硬貨を使って£2を作る方法は何通りあるか?
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
            $this->makeHash(200);

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

