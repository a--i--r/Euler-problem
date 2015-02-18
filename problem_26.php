<?php
class Main {
        
        public $hashMap = array();
 
        private function getCycleLength($in) {
            while ($in % 2 === 0) {
                $in /= 2;
            }
            while ($in % 5 === 0) {
                $in /= 5;
            }
            if ($in === 1) { return 0; }
            $cnt = 1;
            while (1) {
                $pow = gmp_pow(10, $cnt);
                $strPow = gmp_strval($pow);
                $mod = gmp_mod($pow, gmp_init($in));
                $strMod = gmp_strval($mod);
                if (gmp_cmp($mod, 1) === 0) {
                    break;
                }
                $cnt++;
            }
            return $cnt;
        }
        
        public function makeHash($base) {

            $ret = 0;
            $maxCycle = 1;
            $maxIn = 0;
            if (!is_numeric($base) || $base < 2) { return 1; }
            if ($base % 2 === 0) { $base--; }
            for ($i=$base;$i > 1;$i-=2) {
                $cycle = $this->getCycleLength($i);
                echo "${i}:${cycle}";
                if ($cycle === $i - 1) {
                    $ret = $i;
                    break;
                }
                if ($maxCycle < $cycle) {
                    $maxCycle = $cycle;
                    $maxIn = $i;
                }
            }
            if ($ret === 0) {
                $ret = $maxIn;
            }
            
            $this->hashMap[ "${base}" ] = $ret;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "単位分数とは分子が1の分数である. 分母が2から10の単位分数を10進数で表記すると次のようになる. \n\n";
            $strPRB = <<< PRB

1/2 = 0.5
1/3 = 0.(3)
1/4 = 0.25
1/5 = 0.2
1/6 = 0.1(6)
1/7 = 0.(142857)
1/8 = 0.125
1/9 = 0.(1)
1/10 = 0.1

0.1(6)は 0.166666... という数字であり, 1桁の循環節を持つ. 1/7 の循環節は6桁ある.

d < 1000 なる 1/d の中で小数部の循環節が最も長くなるような d を求めよ.
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
            $this->makeHash(1000);

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

