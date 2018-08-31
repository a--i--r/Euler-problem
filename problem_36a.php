<?php
ini_set('memory_limit', '2048M');

class Main {

    public function __construct() {

    }
    
    public static function createPalindrome($input, $base, $bOdd) {
        
        $n = $input;
        $palin = $input;
        
        if ($bOdd) {
            $n = intdiv($n, $base);
        }
        while ($n > 0) {
            $palin = $palin * $base + ($n % $base);
            $n = intdiv($n, $base);
        }
        return $palin;
    }

    public static function isPalindrome($num, $base) {
        
        $reversed = 0;
        $k = $num;
        
        while ( $k > 0 ) {
            $reversed = $base * $reversed + $k % $base;
            $k = intdiv($k,$base);
        }
        
        return $num == $reversed;
    }
    
    public function makeHash() {

        $ret = 0;
        
        $limit = 1000000;
        
        for ($j=0;$j < 2;$j++) {
            $bOdd = ($j % 2 != 0);
            $i = 1;
            while (($num = Main::createPalindrome($i, 10, $bOdd)) < $limit) {
                if (Main::isPalindrome($num, 2)) {
                    $ret += $num;
                }
                $i++;
            }
        }
        
        $base = implode(", ", [$limit]);
        $this->hashMap[ "${base}" ] = $ret;
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null)
    {
        $this->strProblem = "585 = 10010010012 (2進) は10進でも2進でも回文数である.\n\n";
        $strPRB = <<< PRB
100万未満で10進でも2進でも回文数になるような数の総和を求めよ.

(注: 先頭に0を含めて回文にすることは許されない.)
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

