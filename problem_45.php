<?php
ini_set('memory_limit', '2048M');

class Main {

    public      $hashMap = array();
    
    public function __construct() {

    }
    
    // from 44
    private static function isPentagonal($n) {
        
//        $target = (sqrt(1 + 24 * $n) + 1.0) / 6.0;
        
        $arySqrted = gmp_sqrtrem(gmp_add(gmp_mul("24", gmp_init($n)), "1"));
        if (gmp_strval($arySqrted[1]) != "0") { return false; }
        $mod = gmp_strval(gmp_mod(gmp_add($arySqrted[ 0 ], 1), 6));
        return ($mod == "0");
    }
    
    private static function getHexagonalNum($arg) {

        $i = 1;
        while(true) {
            $num = $i * (2 * $i - 1);
            if ($num == $arg) {
                break;
            }
            $i++;
        }
        return $i;
    }
    
    public function getSolution($n) {
        
        $ret = 0;
        
        $start = self::getHexagonalNum($n);
        $i = $start + 1;
        while (true) {
            $num = $i * (2 * $i - 1);
            if (self::isPentagonal($num)) {
                echo "Find Nth hexagonal number:" . $i . "\n";
                break;
            }
            $i++;
        }
        return $num;
    }
   
    public function makeHash($arg) {

        $ret = 0;
        
        $ret = $this->getSolution($arg);
        
        $base = implode(", ", [$arg]);
        $this->hashMap[ "${base}" ] = $ret;
        
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null) {
        
        $this->strProblem = "三角数, 五角数, 六角数は以下のように生成される.\n\n";
        $strPRB = <<< PRB
            
三角数	Tn=n(n+1)/2	1, 3, 6, 10, 15, ...
五角数	Pn=n(3n-1)/2	1, 5, 12, 22, 35, ...
六角数	Hn=n(2n-1)	1, 6, 15, 28, 45, ...

T285 = P165 = H143 = 40755であることが分かる.

次の三角数かつ五角数かつ六角数な数を求めよ.
PRB;

        $this->strProblem .= $strPRB;
        $arg = "40755";

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
