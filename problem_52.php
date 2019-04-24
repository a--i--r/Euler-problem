<?php
ini_set('memory_limit', '2048M');

class Main {

    public      $hashMap = array();
    
    public function __construct() {

    }
    
    private static function isPermutation($m, $n) {
        
        $aryNum = array_fill(0, 10, 0);
        $temp = $n;
        
        while ($temp > 0) {
            $aryNum[ $temp % 10 ]++;
            $temp = intDiv($temp,10);
        }
        
        $temp = $m;
        
        while ($temp > 0) {
            $aryNum[ $temp % 10 ]--;
            $temp = intDiv($temp,10);
        }
        
        for ($i=0;$i < 10;$i++) {
            if ($aryNum[ $i ] != 0) {
                return false;
            }
        }
        return true;
    }
    
    public function getSolution($n) {
        
        $ret = 0;
        $start = 1;
        $found = FALSE;
        
        while (!$found) {
            $start *= 10;
            for ($i = $start;$i < intDiv($start * 10,6); $i++) {
                $found = TRUE;
                for ($j = 2;$j <= 6;$j++) {
                    if (!self::isPermutation($i, $j * $i)) {
                        $found = FALSE;
                        break;
                    }
                }
                if ($found) {
                    $ret = $i;
                    break;
                }
            }
        }
        return $ret;
    }
   
    public function makeHash($arg) {

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
        
        $this->strProblem = "125874を2倍すると251748となる. これは元の数125874と順番は違うが同じ数を含む.\n\n";
        $strPRB = <<< PRB
2x, 3x, 4x, 5x, 6x が x と同じ数を含むような最小の正整数 x を求めよ.
PRB;

        $this->strProblem .= $strPRB;
        $arg = "answer";

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
