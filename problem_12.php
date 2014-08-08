<?php
class Main {
        
        public function getFactors($n) {
            
            if ($n % 2 === 0) { $n /= 2; }
            $divisors = 1;
            $cnt = 0;
            while ($n % 2 === 0) {
                $cnt++;
                $n /= 2;
            }
            $divisors *= ($cnt + 1);
            $p = 3;
            while ($n !== 1) {
                $cnt = 0;
                while ($n % $p === 0) {
                    $cnt++;
                    $n /= $p;
                }
                $divisors *= ($cnt + 1);
                $p += 2;
            }
            return $divisors;
        }
    
        public function findTriangularIndex($factors) {
            
            $n = 1;
            $lnum = $this->getFactors($n);
            $rnum = $this->getFactors($n + 1);
            
            while ($lnum * $rnum < $factors) {
                $n++;
                $lnum = $rnum;
                $rnum = $this->getFactors($n + 1);
            }
            return $n;
        }
        
        public function getTriangleFromIndex($index) {
            
            return ($index * ($index + 1)) / 2;
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "三角数の数列は自然数の和で表わされ, 7番目の三角数は 1 + 2 + 3 + 4 + 5 + 6 + 7 = 28 である。\n\n";
            $strPRB = <<< PRB
最初の7項について, その約数を列挙すると, 以下のとおり。

 1: 1
 3: 1,3
 6: 1,2,3,6
10: 1,2,5,10
15: 1,3,5,15
21: 1,3,7,21
28: 1,2,4,7,14,28

これから, 7番目の三角数である28は, 6個以上の約数をもつ最初の三角数であることが分かる。

では, 500個以上の約数をもつ最初の三角数はいくつか。
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
            $result = $this->getTriangleFromIndex( $this->findTriangularIndex(500) );
            
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