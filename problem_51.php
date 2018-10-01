<?php
ini_set('memory_limit', '2048M');

class Main {

    public      $hashMap = array();
    
    public function __construct() {

    }
    
    public static function gmp_min($a, $b) {
        if (gmp_cmp($a, $b) <= 0) {
            return $a;
        }
        return $b;
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
    
    private static function get5DigitPatterns() {
        
        $ret = array(array());
        
        $ret[0] = [1,0,0,0,1];
        $ret[1] = [0,1,0,0,1];
        $ret[2] = [0,0,1,0,1];
        $ret[3] = [0,0,0,1,1];

        return $ret;
    }
    
    private static function get6DigitPatterns() {
        
        $ret = array(array());
        
        $ret[0] = [1,1,0,0,0,1];
        $ret[1] = [1,0,1,0,0,1];
        $ret[2] = [1,0,0,1,0,1];
        $ret[3] = [1,0,0,0,1,1];
        $ret[4] = [0,1,1,0,0,1];
        $ret[5] = [0,1,0,1,0,1];
        $ret[6] = [0,1,0,0,1,1];
        $ret[7] = [0,0,1,1,0,1];
        $ret[8] = [0,0,1,0,1,1];
        $ret[9] = [0,0,0,1,1,1];
        
        return $ret;
    }
    
    private static function fillPattern($pattern, $num) {
        
        $ret = array();
        $temp = $num;
        $nPattern = count($pattern);
        if ($nPattern <= 0) { return $ret; }
        
        for ($i = $nPattern - 1;$i >= 0;$i--) {
            
            if ($pattern[ $i ] == 1) {
                $ret[ $i ] = $temp % 10;
                $temp /= 10;
            }
            else {
                $ret[ $i ] = -1;
            }
        }
        return $ret;
    }
    
    private static function generateNumber($rep, $fill) {
        
        $temp = 0;
        $nFill = count($fill);
        
        for ($i = 0;$i < $nFill;$i++) {
            $temp = $temp * 10;
            if ($fill[ $i ] == -1) {
                $temp += $rep;
            }
            else {
                $temp += $fill[ $i ];
            }
        }
        return $temp;
    }
    
    private static function familySize($rep, $pattern) {
        
        $size = 1;
        
        for ($i = $rep+1;$i < 10;$i++) {
            if (self::isMillerRabinPrime(self::generateNumber($i, $pattern))) {
                $size++;
            }
        }
        return $size;
    }
    
    public static function isMillerRabinPrime($n, $t=20) {

        if (gmp_cmp($n, "2") < 0) return false;
        if (gmp_cmp($n, "2") == 0) return true;
        if (gmp_cmp(gmp_and($n, "1"),"0") == 0) return false;

//            /* 既知の素数チェック */
//            $ret = self::isPreparedPrime($n);
//            if (is_bool($ret)) { return $ret; }

        /* 既知でないものは自力でチェック */
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
    
    public function getSolution($n) {
        
        $ret = 0;
        for ($i=11;$i < 1000;$i++) {
            if ($i % 5 == 0) continue;
            
            if ($i < 100) {
                $pattern = self::get5DigitPatterns();
            }
            else {
                $pattern = self::get6DigitPatterns();
            }
            
            for ($j = 0;$j < count($pattern[ 0 ]);$j++) {
                for ($k = 0;$k <= 2;$k++) {
                    if ($pattern[ $j ][ 0 ] == 0 && $k == 0) continue;
                    
                    $fill = self::fillPattern($pattern[ $j ], $i);
                    $target = self::generateNumber($k, $fill);
                    if (self::isMillerRabinPrime($target)) {
                        if (self::familySize($k, $fill) == 8) {
                            $ret = $target;
                            break 3;
                        }
                    }
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
        
        $this->strProblem = "*3の第1桁を置き換えることで, 13, 23, 43, 53, 73, 83という6つの素数が得られる.\n\n";
        $strPRB = <<< PRB
56**3の第3桁と第4桁を同じ数で置き換えることを考えよう. 
この5桁の数は7つの素数をもつ最初の例である: 56003, 56113, 56333, 56443, 56663, 56773, 56993. 
            
よって, この族の最初の数である56003は, このような性質を持つ最小の素数である.

桁を同じ数で置き換えることで8つの素数が得られる最小の素数を求めよ. (注:連続した桁でなくても良い)
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
