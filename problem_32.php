<?php
class Main {
        
    private function isPandigital($target) {
        
        $aryTarget = str_split(strval($target));
        sort($aryTarget, SORT_NUMERIC);
        $strTargetSorted = implode("",$aryTarget);
        if ("123456789" == $strTargetSorted) {
            return true;
        }
        return false;
    }
    
    public function makeHash() {

        $ret = 0;
        $base = "";
        $aryPandigital = array();

        // 計算
        for ($m=2;$m < 99;$m++) {
            $nBegin = ($m > 9) ? 123 : 1234;
            $nEnd = 9876 / $m + 1;
           
            for ($n=$nBegin;$n < $nEnd;$n++) {
                $mul = $m * $n;
                $concat = $mul . $m . $n;
                if ($concat >= "1e8" && $concat < "1e9") {
                    if ($this->isPandigital($concat)) {
                        if (!in_array($mul, $aryPandigital)) {
                            $aryPandigital[] = $mul;
                        }
                    }
                }
            }
        }
        sort($aryPandigital, SORT_NUMERIC);
        $base = implode(" * ", $aryPandigital);
        for ($i=0;$i < count($aryPandigital);$i++) {
            $ret += $aryPandigital[ $i ];
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
        $this->strProblem = "すべての桁に 1 から n が一度だけ使われている数をn桁の数がパンデジタル (pandigital) であるということにしよう: \n\n";
        $strPRB = <<< PRB
例えば5桁の数 15234 は1から5のパンデジタルである.

7254 は面白い性質を持っている. 39 × 186 = 7254 と書け, 掛けられる数, 掛ける数, 積が1から9のパンデジタルとなる.

掛けられる数/掛ける数/積が1から9のパンデジタルとなるような積の総和を求めよ.

HINT: いくつかの積は, 1通り以上の掛けられる数/掛ける数/積の組み合わせを持つが1回だけ数え上げよ.
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

