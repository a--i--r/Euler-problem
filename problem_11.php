<?php
class Main {
        
        const PRB_WIDTH = 20;
        const NUM_LENGTH = 4;
        
        public $hashMap = array();
        
        public function makeHash($problem) {
            
            // 分解
            $arySplitted = preg_split("@[ \r\n]+@",$problem,-1,PREG_SPLIT_NO_EMPTY);
            
            // 水平チェック
            $this->makeHorizontalHash($arySplitted);
            
            // 垂直チェック
            $this->makeVerticalHash($arySplitted);
            
            // 斜めチェック
            $this->makeObliqueHash($arySplitted);
            
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        public function makeObliqueHash($splitted) {
            
            for($i=0;$i <= Main::PRB_WIDTH - Main::NUM_LENGTH;$i++) {
                for ($j=0;$j <= Main::PRB_WIDTH - Main::NUM_LENGTH;$j++) {
                    
                    // normal
                    $base = $i * Main::PRB_WIDTH + $j;
                    $combined = "";
                    $calc = 1;
                    for ($k=0;$k < Main::NUM_LENGTH;$k++) {
                        $index = $base + $k * Main::PRB_WIDTH + $k;
                        $combined .= $splitted[ $index ];
                        $calc *= $splitted[ $index ];
                    }
                    if (array_key_exists($combined, $this->hashMap)) { continue; }
                    if ($calc === 0) { continue; }
                    $this->hashMap[ $combined ] = $calc;
                    
                    // reverse
                    $combined = "";
                    $calc = 1;
                    for ($k=0;$k < Main::NUM_LENGTH;$k++) {
                        $index = $base + $k * Main::PRB_WIDTH + (Main::NUM_LENGTH - 1 - $k);
                        $combined .= $splitted[ $index ];
                        $calc *= $splitted[ $index ];
                    }
                    if (array_key_exists($combined, $this->hashMap)) { continue; }
                    if ($calc === 0) { continue; }
                    $this->hashMap[ $combined ] = $calc;
                }
            }
            
        }
        
        public function makeVerticalHash($splitted) {
            
            for($j=0;$j < Main::PRB_WIDTH;$j++) {
                for ($i=0;$i <= Main::PRB_WIDTH - Main::NUM_LENGTH;$i++) {
                
                    $base = $i * Main::PRB_WIDTH + $j;
                    $combined = "";
                    $calc = 1;
                    for ($k=0;$k < Main::NUM_LENGTH;$k++) {
                        $index = $base + $k * Main::PRB_WIDTH;
                        $combined .= $splitted[ $index ];
                        $calc *= $splitted[ $index ];
                    }
                    if (array_key_exists($combined, $this->hashMap)) { continue; }
                    if ($calc === 0) { continue; }
                    $this->hashMap[ $combined ] = $calc;
                }
            }
        }
        
        public function makeHorizontalHash($splitted) {
            
            for ($i=0;$i < Main::PRB_WIDTH;$i++) {
                for ($j=0;$j <= Main::PRB_WIDTH - Main::NUM_LENGTH;$j++) {
                    $base = $i * Main::PRB_WIDTH + $j;
                    $sliced = array_slice($splitted, $base, Main::NUM_LENGTH);
                    $combined = "";
                    $calc = 1;
                    foreach($sliced as $val) {
                        $combined .= $val;
                        $calc *= $val;
                    }
                    if (array_key_exists($combined, $this->hashMap)) { continue; }
                    if ($calc === 0) { continue; }
                    $this->hashMap[ $combined ] = $calc;
                }
            }
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "20×20 の格子のうち, 上下左右斜めのいずれかの方向で連続する4つの数字の積のうち最大のものはいくつか?\n\n";
            $strPRB = <<< PRB
08 02 22 97 38 15 00 40 00 75 04 05 07 78 52 12 50 77 91 08
49 49 99 40 17 81 18 57 60 87 17 40 98 43 69 48 04 56 62 00
81 49 31 73 55 79 14 29 93 71 40 67 53 88 30 03 49 13 36 65
52 70 95 23 04 60 11 42 69 24 68 56 01 32 56 71 37 02 36 91
22 31 16 71 51 67 63 89 41 92 36 54 22 40 40 28 66 33 13 80
24 47 32 60 99 03 45 02 44 75 33 53 78 36 84 20 35 17 12 50
32 98 81 28 64 23 67 10 26 38 40 67 59 54 70 66 18 38 64 70
67 26 20 68 02 62 12 20 95 63 94 39 63 08 40 91 66 49 94 21
24 55 58 05 66 73 99 26 97 17 78 78 96 83 14 88 34 89 63 72
21 36 23 09 75 00 76 44 20 45 35 14 00 61 33 97 34 31 33 95
78 17 53 28 22 75 31 67 15 94 03 80 04 62 16 14 09 53 56 92
16 39 05 42 96 35 31 47 55 58 88 24 00 17 54 24 36 29 85 57
86 56 00 48 35 71 89 07 05 44 44 37 44 60 21 58 51 54 17 58
19 80 81 68 05 94 47 69 28 73 92 13 86 52 17 77 04 89 55 40
04 52 08 83 97 35 99 16 07 97 57 32 16 26 26 79 33 27 98 66
88 36 68 87 57 62 20 72 03 46 33 67 46 55 12 32 63 93 53 69
04 42 16 73 38 25 39 11 24 94 72 18 08 46 29 32 40 62 76 36
20 69 36 41 72 30 23 88 34 62 99 69 82 67 59 85 74 04 36 16
20 73 35 29 78 31 90 01 74 31 49 71 48 86 81 16 23 57 05 54
01 70 54 71 83 51 54 69 16 92 33 48 61 43 52 01 89 19 67 48
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
            $this->makeHash($strPRB);

            // 取り出し
            if (count($this->hashMap) <= 0) { return "Cannot get a return value.\n"; }
            list($val, $key) = array(end($this->hashMap), key($this->hashMap));
            
            $keys = str_split($key, 2);
            $strKeys = implode(' * ', $keys);
            // 結果
            $result = $strKeys . " = " . $val;
            
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

