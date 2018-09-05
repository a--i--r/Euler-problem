<?php
ini_set('memory_limit', '2048M');

class Main {

    private $aryPerm = array();
    public  $hashMap = array();
    
    public function __construct() {

    }
    
    private function swapPerm($i, $j) {

        if (!array_key_exists($i, $this->aryPerm) ||
            !array_key_exists($j, $this->aryPerm)) { return false; }
        
        $temp = $this->aryPerm[ $i ];
        $this->aryPerm[ $i ] = $this->aryPerm[ $j ];
        $this->aryPerm[ $j ] = $temp;
        return true;
    }
    
    public function getSolution($n) {
        
        $ret = "0";
        
        $this->aryPerm = [ 1, 0, 2, 3, 4, 5, 6, 7, 8, 9 ];
        $nPerm = count($this->aryPerm);
        $aryDivisor  = [ 1, 2, 3, 5, 7, 11, 13, 17 ];
        $nDivisor = count($aryDivisor);
        
        $cnt = 1;
        $numPerm = 3265920;
        
        while ($cnt < $numPerm) {
            $i = $nPerm - 1;
            
            while ( $this->aryPerm[ $i - 1 ] >= $this->aryPerm[ $i ]) {
                $i = $i - 1;
            }
            $j = $nPerm;
            while ( $this->aryPerm[ $j - 1 ] <= $this->aryPerm[ $i - 1 ]) {
                $j = $j - 1;
            }

            $this->swapPerm($i - 1, $j - 1);
            $i++;
            $j = $nPerm;
            
            while ($i < $j) {
                $this->swapPerm($i - 1, $j - 1);
                $i++;
                $j--;
            }
            
            $bDiv = true;
            for ($k=1;$k < $nDivisor;$k++) {
                $num = 100 * $this->aryPerm[ $k ] + 10 * $this->aryPerm[ $k + 1 ] + $this->aryPerm[ $k + 2 ];
                if ($num % $aryDivisor[ $k ] != 0) {
                    $bDiv = false;
                    break;
                }
            }
            if ($bDiv) {
                $n = gmp_init("0");
                for ($k=0;$k < $nPerm;$k++) {
                    $n = gmp_add(gmp_mul("10", $n), gmp_strval((string)$this->aryPerm[$k]));
                }
                
                echo "Find number: " . gmp_strval($n) . "\n";
                $ret = gmp_strval(gmp_add(gmp_strval($ret), $n));
            }
            $cnt++;
        }
        
        return $ret;
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
        
        $this->strProblem = "数1406357289は0から9のパンデジタル数である (0から9が1度ずつ現れるので). この数は部分文字列が面白い性質を持っている.\n\n";
        $strPRB = <<< PRB
            
d1を上位1桁目, d2を上位2桁目の数とし, 以下順にdnを定義する. この記法を用いると次のことが分かる.

d2d3d4=406 は 2 で割り切れる
d3d4d5=063 は 3 で割り切れる
d4d5d6=635 は 5 で割り切れる
d5d6d7=357 は 7 で割り切れる
d6d7d8=572 は 11 で割り切れる
d7d8d9=728 は 13 で割り切れる
d8d9d10=289 は 17 で割り切れる
            
このような性質をもつ0から9のパンデジタル数の総和を求めよ.
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
