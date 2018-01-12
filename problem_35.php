<?php
ini_set('memory_limit', '5120M');

class Main {

    public static $primes;
    public static $primesLength;
    public static $primesString = "2 3 5 7 11 13 17 19 23 29 31 37 41 43 47 53 
59 61 67 71 73 79 83 89 97 101 103 107 109 113 127 
131 137 139 149 151 157 163 167 173 179 181 191 193 197 199 
211 223 227 229 233 239 241 251 257 263 269 271 277 281 283 
293 307 311 313 317 331 337 347 349 353 359 367 373 379 383 
389 397 401 409 419 421 431 433 439 443 449 457 461 463 467 
479 487 491 499 503 509 521 523 541 547 557 563 569 571 577 
587 593 599 601 607 613 617 619 631 641 643 647 653 659 661 
673 677 683 691 701 709 719 727 733 739 743 751 757 761 769 
773 787 797 809 811 821 823 827 829 839 853 857 859 863 877 
881 883 887 907 911 919 929 937 941 947 953 967 971 977 983 
991 997";
    
    public function __construct() {

        self::$primesString = preg_replace("@\n|\r|\r\n|\s+@", " ", self::$primesString);
        self::$primes = split(" ", self::$primesString);
        self::$primesLength = count(self::$primes);
    }
    
    public static function isPreparedPrime($n) {

        for ($i = 0;$i < self::$primesLength;$i++) {
            $prime = gmp_init(self::$primes[ $i ]);
            if (gmp_cmp($n, $prime) == 0) {
                return true;
            }
            if (gmp_cmp(gmp_mod($n, $prime), "0") == 0) {
                return false;
            }
        }
        return null;
    }
    
    public static function gmp_rand($min, $max, $factor = 5) {
        $rand = gmp_random( $factor );
        if (gmp_cmp($rand, $min) <= 0) {
            return $min;
        }
        else if (gmp_cmp($rand, $max) > 0) {
            return gmp_mod($rand, gmp_add($max, "1"));
        }
        else {
            return $rand;
        }
    }
    
    public static function isMillerRabinPrime($n, $t=10) {

        if (gmp_cmp($n, "2") < 0) return false;
        if (gmp_cmp($n, "2") == 0) return true;
        if (!gmp_and($n, "1")) return false;

        // 既知の素数チェック
        $ret = self::isPreparedPrime($n);
        if (is_bool($ret)) { return $ret; }

        // 既知でないものは自力でチェック
        $d = gmp_sub($n, 1);
        $s = gmp_init("0");
        while (gmp_cmp(gmp_mod($d, 2), "0") == 0) {
            $d = gmp_div($d, "2");
            $s = gmp_add($s, "1");
        }
        for ($i=gmp_init("0");gmp_cmp($i, $t) < 0;$i = gmp_add($i, "1")) {
            // 2,..,n-1からランダムに値を選ぶ
            $val = self::gmp_rand(2, gmp_sub($n, "1"));
            $x = gmp_init(bcpowmod(gmp_strval($val), gmp_strval($d), gmp_strval($n)));
            if (gmp_cmp($x, "1") == 0 || gmp_cmp($x, gmp_sub($n, "1")) == 0) {
                 continue;
            }

            for ($j=gmp_init("0");gmp_cmp($j, $s) < 0;$j = gmp_add($j, "1")) {
                $x = gmp_init(bcmod(bcmul(gmp_strval($x), gmp_strval($x)), gmp_strval($n)));
                if (gmp_cmp($x, "1") == 0) { return false; }
                if (gmp_cmp($x, gmp_sub($n, "1")) == 0) { continue 2; }
            }
            return false;
        }
        return true;
    }

    public static function make1379num($max) {
        
        $aryRet = array();
        $ary1379 = array(1,3,7,9);
        $mc = 0;
        $m = 0;
        while ($m < $max) {
            
            if (count($aryRet) <= 0) {
                $m = 0;
            }
            else {
                if ($mc > count($aryRet)) { break; }
                $m = $aryRet[ $mc++ ];
            }
            for ($i=0;$i<count($ary1379);$i++) {
                $n = $ary1379[ $i ];
                $num = intval($m . $n);
                if ($num >= $max) {
                    break 2;
                }
                $aryRet[] = $num;
            }
            
        }
        return $aryRet;
    }
    
    public static function getCircularPrimes($aryTarget) {
        
        $aryRet = array();
        if (!is_array($aryTarget) || count($aryTarget) <= 0) { return $aryRet; }
        for ($i=0;$i < count($aryTarget);$i++) {
            
            $target = $aryTarget[ $i ];
            $len = strlen($target);
            
            for ($j=0;$j < $len;$j++) {
                $rotated = substr($target, $j, $len) . substr($target, 0, $j);
                if (!self::isMillerRabinPrime($rotated)) {
                    continue 2;
                }
            }
            $aryRet[] = $target;
        }
        return $aryRet;
    }
    
    public function makeHash() {

        $ret = 0;
        $base = "";
        $aryFact = array();
        $aryNum = array();

        // 計算
        $ary1379num = $this->make1379num(1000000);
        $ary1379num[] = 2;
        $ary1379num[] = 5;
        
        // 循環素数取得
        $aryCircularPrimes = $this->getCircularPrimes($ary1379num);
        sort($aryCircularPrimes);
        //var_dump($aryCircularPrimes);
        $ret = count($aryCircularPrimes);
        
        $base = implode(", ", $aryCircularPrimes);
        $this->hashMap[ "${base}" ] = $ret;
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null)
    {
        $this->strProblem = "197は巡回素数と呼ばれる. 桁を回転させたときに得られる数 197, 971, 719 が全て素数だからである. \n\n";
        $strPRB = <<< PRB
100未満には巡回素数が13個ある: 2, 3, 5, 7, 11, 13, 17, 31, 37, 71, 73, 79, および97である.

100万未満の巡回素数はいくつあるか?
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

