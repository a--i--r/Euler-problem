<?php
ini_set('memory_limit', '2048M');

class Main {

    public function __construct() {

    }
    
    public function getSolution($n) {
        
        $ret = 1;
        for ($i=0;$i < $n;$i++) {
            $ret *= $this->getDigit(10 ** $i);
        }
        return $ret;
    }
    
    public function getDigit($n) {
        
        $r = $s = $k = 0;
        echo "\$n = $n : ";
        
        while ( $s < $n ) {
            $r = $s;
            $s += 9 * 10 ** $k++ * $k;
        }
        $h = $n - $r - 1;
        $t = 10 ** ($k - 1) + intdiv($h , $k);
        $p = $h % $k;
        
        $ret = substr($t,$p,1);
        echo "\$ret = $ret\n";
        return $ret;
    }
    
    public function makeHash($arg) {

        $ret = 0;
        
        $ret = $this->getSolution(7);
        
        $base = implode(", ", [$arg]);
        $this->hashMap[ "${base}" ] = $ret;
        
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null) {
        
        $this->strProblem = "正の整数を順に連結して得られる以下の10進の無理数を考える:\n\n";
        $strPRB = <<< PRB
            
0.123456789101112131415161718192021...

小数第12位は1である.

dnで小数第n位の数を表す. d1 × d10 × d100 × d1000 × d10000 × d100000 × d1000000 を求めよ.
PRB;

        $this->strProblem .= $strPRB;
        $arg = "d1 × d10 × d100 × d1000 × d10000 × d100000 × d1000000";

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
