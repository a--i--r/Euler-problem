<?php
class Main {
        
    public function makeHash() {

        $ret = 0;
        $base = "";
        $aryFact = array();
        $aryNum = array();

        // 計算
        for ($j=0;$j <= 9;$j++) {
            $aryFact[ $j ] = gmp_strval(gmp_fact($j));
        }
        $limit=$aryFact[9]*7;
        
        for ($i=10;$i < $limit;$i++) {
            $sum = "0";
            $num = $i;
            while ($num > 0) {
                if (array_key_exists($num, $aryFact)) {
                    $sum += $aryFact[$num];
                    break;
                }
                else {
                    $mod = $num % 10;
                    $num = intval(floor($num / 10));
                    $sum += $aryFact[$mod];
                }
            }
            $aryFact[$i] = $sum;
            if ($sum == $i) {
                $ret += $i;
                $aryNum[] = $i;
            }
        }
        $base = implode(" * ", $aryNum);
        $this->hashMap[ "${base}" ] = $ret;
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null)
    {
        $this->strProblem = "145は面白い数である. 1! + 4! + 5! = 1 + 24 + 120 = 145となる. \n\n";
        $strPRB = <<< PRB
各桁の数の階乗の和が自分自身と一致するような数の和を求めよ.

注: 1! = 1 と 2! = 2 は総和に含めてはならない.
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

