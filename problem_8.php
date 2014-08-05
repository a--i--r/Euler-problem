<?php
    class Main {
        
        const CONTINUES = 13;
        public $hashNumbers = array();
        
       public static function gmp_sort($a) {
           $sortArray = $a;
           usort($sortArray, "gmp_cmp");
           return $sortArray;
       }
        
        public function makeHash($str) {
            
            $length = strlen($str);
            if ($length <= 0) { return $this->hashNumbers; }
            
            $str = strtolower($str);
            for($i=0;$i <= $length - Main::CONTINUES;$i++) {
                
                $substr = substr($str, $i, Main::CONTINUES);
                // 0は除外してOK
                if (($index = strpos($substr, '0')) !== FALSE) { 
                    $i += $index + 1;
                    continue;
                }
                // 同じなら skip
                if (array_key_exists($substr, $this->hashNumbers)) {
                    continue;
                }
                // 計算
                $sum = "1";
                for ($j=0;$j < strlen($substr);$j++) {
                    $sum = gmp_strval(gmp_mul($sum, $substr[ $j ]));
                }
                $this->hashNumbers[ $substr ] = $sum;
            }
            
            uasort($this->hashNumbers, "gmp_cmp");   
            
            return $this->hashNumbers;
        }
        
        
        
        /**
         * Project euler problem 8
         */
        public function execute($args=null)
        {
            $this->strProblem = "以下の1000桁の数字から13個の連続する数字を取り出して その積を計算する。\n そのような積の中で最大のものの値はいくらか。\n";
            $arg = "7316717653133062491922511967442657474235534919493496983520312774506326239578318016984801869478851843858615607891129494954595017379583319528532088055111254069874715852386305071569329096329522744304355766896648950445244523161731856403098711121722383113622298934233803081353362766142828064444866452387493035890729629049156044077239071381051585930796086670172427121883998797908792274921901699720888093776657273330010533678812202354218097512545405947522435258490771167055601360483958644670632441572215539753697817977846174064955149290862569321978468622482839722413756570560574902614079729686524145351004748216637048440319989000889524345065854122758866688116427171479924442928230863465674813919123162824586178664583591245665294765456828489128831426076900422421902267105562632111110937054421750694165896040807198403850962455444362981230987879927244284909188845801561660979191338754992005240636899125607176060588611646710940507754100225698315520005593572972571636269561882670428252483600823257530420752963450";
            
            $result = "";
            if (is_array($args) && array_key_exists(0, $args)) {
                list($arg) = $args;
            }
            
            $this->strProblem .= $arg . "\n";

            if (strlen($this->strProblem) > 0) {
                echo $this->strProblem;
            }
            
            // 計算
            $hashNumbers = $this->makeHash($arg);
            if (!$hashNumbers) { echo "Cannot get a return value.\n"; }
            list($val, $key) = array(end($hashNumbers), key($hashNumbers));
            $result = $key . " (" . $val . ") \n";

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

