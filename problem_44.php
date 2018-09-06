<?php
ini_set('memory_limit', '2048M');

class Main {

    public  $hashMap = array();
    
    public function __construct() {

    }
    
    private static function isPentagonal($n) {
        
//        $target = (sqrt(1 + 24 * $n) + 1.0) / 6.0;
        
        $arySqrted = gmp_sqrtrem(gmp_add(gmp_mul("24", gmp_init($n)), "1"));
        if (gmp_strval($arySqrted[1]) != "0") { return false; }
        $mod = gmp_strval(gmp_mod(gmp_add($arySqrted[ 0 ], 1), 6));
        return ($mod == "0");
    }
    
    public function getSolution() {
        
        $ret = 0;
        $bNotFound = true;
        $i = 1;
        $k = 0;
        
        while ($bNotFound) {
            $i++;
            $n = intdiv($i * (3 * $i - 1), 2);
//            echo "i:$i\n";
            for ($j=$i-1;$j > 0;$j--) {
                $m = intdiv($j * (3 * $j - 1), 2);

                if (self::isPentagonal($n - $m) && self::isPentagonal($n + $m)) {
                    $ret = $n - $m;
                    $k = $j;
                    $bNotFound = false;
                    echo "i:$i/j:$j\n";
                    break;
                }
            }
        }
        return $ret;
    }
   
    public function makeHash($arg) {

        $ret = 0;
        
        $ret = $this->getSolution();
        
        $base = implode(", ", [$arg]);
        $this->hashMap[ "${base}" ] = $ret;
        
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null) {
        
        $this->strProblem = "五角数は Pn = n(3n-1)/2 で生成される.\n\n";
        $strPRB = <<< PRB
            
最初の10項は 1, 5, 12, 22, 35, 51, 70, 92, 117, 145, ...である.

P4 + P7 = 22 + 70 = 92 = P8 である. しかし差 70 - 22 = 48 は五角数ではない.

五角数のペア Pj と Pk について, 差と和が五角数になるものを考える. 差を D = |Pk - Pj| と書く. 差 D の最小値を求めよ.
PRB;

        $this->strProblem .= $strPRB;
        $arg = "result";

        $result = "";
        if (is_array($args) && array_key_exists(0, $args)) {
            list($arg) = $args;
        }

        $this->strProblem .= "[ $arg ]\n";

        if (strlen($this->strProblem) > 0) {
            echo $this->strProblem;
        }

        // 計算
        $this->makeHash($arg);

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
