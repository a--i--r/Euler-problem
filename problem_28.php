<?php
class Main {
        
        public function makeHash($base) {

            $n = ($base -1) / 2;
            $ret = (16 * pow($n, 3) + 30 * pow($n, 2) + 26 * $n + 3) / 3;
            
            $this->hashMap[ "${base}" ] = $ret;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "1から初めて右方向に進み時計回りに数字を増やしていき, 5×5の螺旋が以下のように生成される: \n\n";
            $strPRB = <<< PRB
21	22	23	24	25
20	7	8	9	10
19	6	1	2	11
18	5	4	3	12
17	16	15	14	13
両対角線上の数字の合計は101であることが確かめられる.

1001×1001の螺旋を同じ方法で生成したとき, 対角線上の数字の和はいくつか?
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
            $this->makeHash(1001);

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

