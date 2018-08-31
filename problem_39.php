<?php
ini_set('memory_limit', '2048M');

class Main {

    public function __construct() {

    }
    
    public function makeHash($arg) {

        $ret = 0;
        $retVal = 0;
        for ($p=2;$p <= $arg;$p++) {
            $tempRet = 0;
            for ($a = 2; $a < ($p/3); $a++) {
                if ($p * ($p - 2 * $a) % (2 * ($p - $a)) == 0) {
                    $tempRet++;
                }
                if ($tempRet > $retVal) {
                    $retVal = $tempRet;
                    $ret = $p;
                }
                
            }
        }
        
        $base = implode(", ", [$arg]);
        $this->hashMap[ "${base}" ] = $ret;
        
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null) {
        
        $this->strProblem = "辺の長さが {a,b,c} と整数の3つ組である直角三角形を考え, その周囲の長さを p とする. p = 120のときには3つの解が存在する:\n\n";
        $strPRB = <<< PRB
            
{20,48,52}, {24,45,51}, {30,40,50}

p ≤ 1000 のとき解の数が最大になる p はいくつか?
PRB;

        $this->strProblem .= $strPRB;
        $arg = "1000";

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
