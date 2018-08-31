<?php
ini_set('memory_limit', '2048M');

class Main {

    
    protected $aryPrimes;
    
    public function __construct() {

        $this->aryPrimes = array();
    }
    
    public function isPrime($input) {
        return in_array($input, $this->aryPrimes);
    }
    
    public function doSeive($limit) {

        $sieveBound = gmp_strval(gmp_div(gmp_sub($limit, 1), 2));
        $upperSqrt = gmp_strval(gmp_div(gmp_sub(gmp_sqrt($limit), 1), 2));

        $bitArray = array_fill(0, $sieveBound+1, 1);
        for ($i="1";gmp_cmp($i, $upperSqrt) <= 0;$i = gmp_add($i, 1)) {
            for($j=gmp_mul(gmp_mul($i, 2),gmp_add($i, 1));gmp_cmp($j, $sieveBound) <= 0;$j = gmp_add($j, gmp_add(gmp_mul($i, 2),1))) {
                $t = gmp_intval($j);
                $bitArray[ $t ] = 0;
            }
        }
        $numbers = array("2");
        for ($i="1";gmp_cmp($i, $sieveBound) <= 0;$i = gmp_add($i, 1)) {
            if ($bitArray[ gmp_intval($i) ]) {
                $numbers[] = gmp_strval(gmp_add(gmp_mul(2, $i), 1));
            }
        }
        return $numbers;
    }
    
    public function getBothTrunkPrimes($limit) {
        
        $ret = array();
        $i = 4;
        
        $this->aryPrimes = $this->doSeive($limit);
        $count = 0;
        while ($count < 11) {
            $rightTrunk = $this->aryPrimes[ $i ];
            $leftTrunk = 0;
            $multiplier = 1;
            $bTrunkPrime = true;
            while ($rightTrunk > 0 && $bTrunkPrime) {
                $leftTrunk += $multiplier * ($rightTrunk % 10);
                $bTrunkPrime = $this->isPrime($leftTrunk) && $this->isPrime($rightTrunk);
                $rightTrunk = intdiv($rightTrunk, 10);
                $multiplier *= 10;
            }
            if ($bTrunkPrime) {
                $count++;
                $ret[] = $this->aryPrimes[ $i ];
            }
            $i++;
        }
        return $ret;
    }
    
    public function makeHash($arg) {

        $ret = 0;
        
        $limit = $arg;
        
        $ret = $this->getBothTrunkPrimes($limit);
       
        $base = implode(", ", $ret);
        $this->hashMap[ "${base}" ] = array_sum($ret);
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null)
    {
        $this->strProblem = "3797は面白い性質を持っている. まずそれ自身が素数であり, 左から右に桁を除いたときに全て素数になっている (3797, 797, 97, 7)\n\n";
        $strPRB = <<< PRB
同様に右から左に桁を除いたときも全て素数である (3797, 379, 37, 3).

右から切り詰めても左から切り詰めても素数になるような素数は11個しかない. 総和を求めよ.

注: 2, 3, 5, 7を切り詰め可能な素数とは考えない.
PRB;

        $this->strProblem .= $strPRB;
        $arg = "1000000";

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
?>

