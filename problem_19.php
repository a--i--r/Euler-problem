<?php
class Main {
        
        public static $weeks = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    
        public $hashMap = array();
        
        public function makeHash() {

            $ary = array();
            $from = "19010101";
            $to = "20001231";
            
            $cnt = 0;
            $objDate = new DateTime($from);
            while ( $objDate->format('Ymd') <= $to ) {
            
                $week = $objDate->format('w');
                if ($week == 0) {
                    $cnt++;
                }
                $year = $objDate->format('Y');
                $month = $objDate->format("n");
                $day = $objDate->format('j');
                
                if ($month == 12) {
                    $year++;
                    $month = 1;
                }
                else {
                    $month++;
                }
                $objDate = new DateTime(sprintf("%04d%02d%02d",$year,$month,$day));
            }
            
            
            $this->hashMap[ $from ] = $cnt;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "1900年1月1日は月曜日である.\n\n";
            $strPRB = <<< PRB
20世紀（1901年1月1日から2000年12月31日）中に月の初めが日曜日になるのは何回あるか?

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
            $this->makeHash();

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

