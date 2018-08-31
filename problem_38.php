<?php
ini_set('memory_limit', '2048M');

class Main {

    public function __construct() {

    }
    
    private function isPandigital($target) {
        
        $aryTarget = str_split(strval($target));
        sort($aryTarget, SORT_NUMERIC);
        $strTargetSorted = implode("",$aryTarget);
        if ("123456789" == $strTargetSorted) {
            return true;
        }
        return false;
    }
    
    public function makeHash($arg) {

        $ret = 0;
        
        for ($i=9387;$i > 9284;$i--) {
            $ret = $i . 2*$i;
            if ($this->isPandigital($ret)) {
                break;
            }
        }
        $base = implode(", ", [$ret]);
        $this->hashMap[ "${base}" ] = $ret;
        
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null) {
        
        $this->strProblem = "192 に 1, 2, 3 を掛けてみよう.\n\n";
        $strPRB = <<< PRB
192 × 1 = 192
192 × 2 = 384
192 × 3 = 576

積を連結することで1から9の パンデジタル数 192384576 が得られる. 192384576 を 192 と (1,2,3) の連結積と呼ぶ.

同じようにして, 9 を 1,2,3,4,5 と掛け連結することでパンデジタル数 918273645 が得られる. これは 9 と (1,2,3,4,5) との連結積である.

整数と (1,2,...,n) (n > 1) との連結積として得られる9桁のパンデジタル数の中で最大のものはいくつか?
PRB;

        $this->strProblem .= $strPRB;
        $arg = "";

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
