<?php
ini_set('memory_limit', '2048M');

class Main {

    public  $hashMap = array();
    
    public function __construct() {

    }
    private static function getPermutation($n) {

         if ( $n < 0) {
             return 0;
         }
         $p = 1;
         for ($i=1;$i <= $n;$i++) {
             $p *= $i;
         }
         return $p;
    }
   
    private static function getPandigital($base) {
        
        $aryNum = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );

        $ret = "";
        $remain = $base - 1;
        $n = count($aryNum);
        for ($i=1;$i < $n;$i++) {
            $p = self::getPermutation($n - $i);
            $fl = floor($remain / $p);
            $remain = $remain % $p;
            $ret .= $aryNum[ $fl ];
            array_splice($aryNum, $fl, 1);
            if ($remain == 0) {
                break;
            }
        }
        for ($i=0;$i < count($aryNum);$i++) {
            $ret .= $aryNum[ $i ];
        }
        return $ret;
    }
    
    public function getSolution($n) {
        
        $ret = "0";
        $aryDivisors = [ 1, 2, 3, 5, 7, 11, 13, 17 ];
        $nDivisors = count($aryDivisors);
        
        for ($i=1;$i < 3265920;$i++) {
            $bDiv = true;
            $strNum = self::getPandigital($i);
//            if ($strNum == "1406357289") {
//                echo "OK";
//            }
            $aryNum = str_split($strNum, 1);
            for ($k=1;$k < $nDivisors;$k++) {
                $num = 100 * $aryNum[$k] + 10 * $aryNum[ $k+1 ] + $aryNum[ $k+2 ];
                if ($num % $aryDivisors[ $k ] != 0) {
                    $bDiv = false;
                    break;
                }
            }
            if ($bDiv) {
                echo "Find pandigital:" . $strNum . "\n";
                $ret = gmp_strval(gmp_add($ret, $strNum));
            }
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
