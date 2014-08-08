<?php
    class Main {
        
        public function findPythagoreanTriplet(&$a, &$b, &$c, $s) {
            
            $m = $k = $n = $d = 0;
            $bFound = FALSE;
            
            $mLimit = (integer)sqrt($s/2);
            for ($m=2;$m <= $mLimit;$m++) {
                
                // found m
                if (($s / 2) % $m == 0) {
                    // odd number for $k
                    if ($m % 2 == 0) {
                        $k = $m + 1;
                    }
                    else {
                        $k = $m + 2;
                    }
                    while ( $k < 2 * $m && $k <= (integer)($s / (2 * $m)) ) {
                        if ($s / (2 * $m) % $k == 0 && gmp_cmp(gmp_gcd($k, $m), 1) == 0) {
                            $d = (integer)((integer)($s / 2) / ($k * $m));
                            $n = $k - $m;
                            $a = $d * ($m * $m - $n * $n);
                            $b = 2 * $d * $n * $m;
                            $c = $d * ($m * $m + $n * $n);
                            $bFound = TRUE;
                            break 2;
                        }
                        $k += 2;
                    }
                }
            }
            $arr = array($a, $b, $c);
            sort($arr);
            list($a, $b, $c) = $arr;
            return $bFound;
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "ピタゴラス数(ピタゴラスの定理を満たす自然数)とは a < b < c で以下の式を満たす数の組である。\n a**2 + b**2 = c**2\n\n";
            $this->strProblem .= "a + b + c = 1000 となるピタゴラスの三つ組が一つだけ存在する。\n これらの積 abc を計算しなさい。\n";
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
            if (!$this->findPythagoreanTriplet($a, $b, $c, 1000)) {
                $result = "Cannot solve this program. TT";
            }
            else {
                $result = "$a * $b * $c = " . $a * $b * $c;
            }

            return $result;
        }

    }
    
    $time_start = microtime(true);
    
    $main = new Main();
    
    echo "result: " . $main->execute() . "\n";

    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "time elapsed: " . $time . " sec.";
?>

