<?php
class Main {
        
        public $hashMap = array();
       
        public function makeHash($problem) {

            // 配列準備
            $aryProblem = array();
            $aryLines = preg_split("@[\r\n]+@",$problem,-1,PREG_SPLIT_NO_EMPTY);
            for($j=0;$j < count($aryLines);$j++) {
                $line = $aryLines[ $j ];
                $ary = explode(' ', $line);
                for ($i=0;$i < count($aryLines);$i++) {
                    if (count($ary) > $i) {
                        $aryProblem[ $j ][ $i ] = $ary[ $i ];
                    }
                    else {
                        $aryProblem[ $j ][ $i ] = "00";
                    }
                }
            }
            
            // 後ろから検索
            for ($i=count($aryLines) - 2;$i >= 0;$i--) {
                for ($j=0;$j <= $i;$j++) {
                    $aryProblem[ $i ][ $j ] += max(array($aryProblem[ $i+1 ][ $j ], $aryProblem[ $i+1 ][ $j+1 ]));
                }
            }
            
            $this->hashMap[ count($aryLines) ] = $aryProblem[ 0 ][ 0 ];
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "以下の三角形の頂点から下まで移動するとき, その数値の和の最大値は23になる.\n\n";
            $strPRB = <<< PRB
3
7 4
2 4 6
8 5 9 3
この例では 3 + 7 + 4 + 9 = 23.

以下の三角形を頂点から下まで移動するとき, その最大の和を求めよ.

PRB;

            $strTriangle = <<< INPUT_PRB
75
95 64
17 47 82
18 35 87 10
20 04 82 47 65
19 01 23 75 03 34
88 02 77 73 07 63 67
99 65 04 28 06 16 70 92
41 41 26 56 83 40 80 70 33
41 48 72 33 47 32 37 16 94 29
53 71 44 65 25 43 91 52 97 51 14
70 11 33 28 77 73 17 78 39 68 17 57
91 71 52 38 17 14 91 43 58 50 27 29 48
63 66 04 68 89 53 67 30 73 16 69 87 40 31
04 62 98 27 23 09 70 98 73 93 38 53 60 04 23
INPUT_PRB;

            $strPRB .= $strTriangle;
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
            $this->makeHash($strTriangle);

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

