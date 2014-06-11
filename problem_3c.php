<?php
    class Main {

        public static $primes;
        public static $primesLength;
        public static $primesString = "2 3 5 7 11 13 17 19 23 29 31 37 41 43 47 53 59 61 67 71 73 79 83 89 97 101 103 107 109 113 127 131 137 139 149 151 157 163 167 173 179 181 191 193 197 199 211";
        public static $indices = array(
                                                                1, 11, 13, 17, 19, 23, 29, 31, 37, 41,
                                                                43, 47, 53, 59, 61, 67, 71, 73, 79, 83,
                                                                89, 97,101,103,107,109,113,121,127,131,
                                                                137,139,143,149,151,157,163,167,169,173,
                                                                179,181,187,191,193,197,199,209);
        // distances between sieve values
        public static $offsets = array(
                                                                10, 2, 4, 2, 4, 6, 2, 6, 4, 2, 4, 6,
                                                                6, 2, 6, 4, 2, 6, 4, 6, 8, 4, 2, 4,
                                                                2, 4, 8, 6, 4, 6, 2, 4, 6, 2, 6, 6,
                                                                4, 2, 4, 6, 2, 6, 4, 2, 4, 2,10, 2);
        public static $pk = array ( 0,1,2,3,5,6,7,10,11,13,14,15,17,0 );
        public static $checkStart = 212;
        public static $maxInt = 2147483647;
        
        // 篩の素数幅
        public static $sieveWidth = 7;
        // 篩の大きさ
        public static $sieveBound = 6;

        public function __construct() {

            self::$primesString = preg_replace("@\n|\r|\r\n@", " ", self::$primesString);
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

        public static function getPreparedFactor($n) {

            for ($i=0;$i < self::$primesLength;$i++) {
                $prime = gmp_init(self::$primes[ $i ]);
                if (gmp_cmp($n, $prime) ==0) {
                    return $prime;
                }
                if (gmp_cmp(gmp_mod($n, $prime), "0") == 0) {
                    return $prime;
                }
            }
            if (gmp_cmp($n, gmp_init(self::$checkStart)) < 0) {
                return gmp_init("1");
            }

            return null;
        }

        public static function isMillerRabinPrime($n, $t=20) {

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

        public static function print_r_nlf($str) {
            
            $echoString = print_r($str, true);
            echo preg_replace('@[\r|\n ]+@','', $echoString);
        }
        
        public static function print_matrix($matrix, $name) {
            echo "----- ${name} -----\n";

            $notMatrix = false;
            foreach ($matrix as $row) {
                
                if (is_array($row)) {
                    foreach ($row as $col) {
                        printf("%2d ", $col);
                    }
                    echo "\n";
                }
                else {
                    $notMatrix = true;
                    printf("%2d ", $row);
                }
            }
            if ($notMatrix) {
                echo "\n";
            }
        }
        public static function isFermatPrime($n) {

            if (gmp_cmp($n, "2") < 0) return false;
            if (gmp_cmp($n, "2") == 0) return true;
            if (!gmp_and($n, "1")) return false;

            // 既知の素数チェック
            $ret = self::isPreparedPrime($n);
            if (is_bool($ret)) { return $ret; }

            return (gmp_cmp(bcpowmod("2", gmp_strval(gmp_sub($n, "1")), gmp_strval($n)), "1") == 0);
        }

        public static function factorBrent($a, $b, $c) {
            return gmp_mod(gmp_add(gmp_mod(gmp_mul($a, $a), $b), $c), $b);
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
        
        public static function gmp_legendre($a, $m) {
            return gmp_powm($a, gmp_div($m-1, 2), $m);
        }

        public static function getBrent($n) {

            if (gmp_cmp(gmp_mod($n, "2"), "0") == 0) { return 2; }

            // 既知の素数チェック
            $ret = self::getPreparedFactor($n);
            if ($ret !== null) { return $ret; }

            // 既知でないものは自力で探す
            $y = self::gmp_rand("1", gmp_sub($n, "1"));
            $c = self::gmp_rand("1", gmp_sub($n, "1"));
            $m = self::gmp_rand("1", gmp_sub($n, "1"));
            $g = gmp_init("1");
            $r = gmp_init("1");
            $q = gmp_init("1");
            while ( gmp_cmp($g, "1") == 0 ) {
                $x = gmp_init(gmp_strval($y));
                for ($i = gmp_init("0"); gmp_cmp($i, $r) <= 0; $i = gmp_add($i, "1")) {
                    #ti = gmp_strval($i);
                    #$tr = gmp_strval($r);
                    $y = self::factorBrent($y, $n, $c);
                }
                $k = gmp_init("0");
                while ( gmp_cmp($k, $r) < 0  && gmp_cmp($g, "1") == 0 ) {
                    $ys = gmp_init(gmp_strval($y));
                    $min = self::gmp_min($m, gmp_sub($r, $k));
                    for ($i = gmp_init("0"); gmp_cmp($i, $min) <= 0; $i = gmp_add($i, "1")) {
                        $y = self::factorBrent($y, $n, $c);
                        //$y = fmod(fmod($y * $y, $n) + $c, $n);
                        $q = gmp_mod(gmp_mul($q, (gmp_abs(gmp_sub($x, $y)))), $n);
                    }

                    $g = gmp_gcd($q, $n);
                    $k = gmp_add($k, $m);
                }
                $r = gmp_mul($r, "2");
            }

            if (gmp_cmp($g, $n) == 0) {
                while ( true ) {
                    $ys = self::factorBrent($ys, $n, $c);
                    //$ys = fmod(fmod($ys * $ys, $n) + $c, $n);
                    $g = gmp_gcd(gmp_abs(gmp_sub($x, $ys)), $n);
                    if (gmp_cmp($g, "1") > 0) {
                        break;
                    }
                }
            }
            return $g;
        }

        public static function gmp_modsqrt($a, $b) {
            return gmp_mod(gmp_sqrt($a), $b);
        }

        public static function gmp_modinv($a, $b) {
            $a = gmp_mod($a, $b);
            $x = gmp_init("0");
            $u = gmp_init("1");
            while ( gmp_strval($a) ) {
                $x = $u;
                $u = gmp_sub($x , gmp_mul(gmp_div($b, $a), $u));
                $m = $a;
                $a = gmp_mod($m, $a);
            }
            return $x;
        }
        
        public static function array_xor($a, $b) {
            return array_merge(array_diff($a, $b), array_diff($b, $a));
        }

       public static function gmp_sort($a) {
           $sortArray = clone($a);
           usort($sortArray, "gmp_cmp");
           return $sortArray;
       }

        public static function getNextPrime($n) {

            if (gmp_cmp($n, "2") < 0) {
                return 2;
            }
            $n = gmp_or(gmp_add($n, "1"), "1");
            if (gmp_cmp($n, gmp_init(self::$checkStart)) < 0) {
                while ( 1 ) {
                    for ($i=0;$i < count(self::$primes);$i++) {
                        if (gmp_cmp($n, gmp_init(self::$primes[ $i ])) == 0) {
                            return $n;
                        }
                    }
                    $n = gmp_add($n, "2");
                }
            }

            $x = gmp_intval(gmp_mod($n, "210"));
            $s = 0;
            $e = 47;
            $m = 24;
            while ($m != $e) {
                if (self::$indices[ $m ] < $x) {
                    $s = $m;
                    $m = ($s + $e + 1) >> 1;
                }
                else {
                    $e = $m;
                    $m = ($s + $e) >> 1;
                }
            }
            $i = gmp_add($n, gmp_init(self::$indices[ $m ] - $x));

            $start = array_slice(self::$offsets, $m);
            $end = array_slice(self::$offsets, 0, $m);
            $sum = array_merge($start, $end);

            while ( 1 ) {
                foreach( $sum as $val ) {
                    if (self::isFermatPrime($i)) {
                        return $i;
                    }
                    $i = gmp_add($i, gmp_init($val));
                }
            }
        }

        /**
         * Input number to be factored N, find best multiplier k
         */
        public static function knuth($mm, &$epr,  &$N, &$D) {
            
            /* set D= k * N */
            $T = gmp_init("0");
            $fks = $dp = $top = 0;
            $bFound = false;
            $i = $j = $bk = $nk = $kk = $rem = $p = 0;
            
            $top = (-10.0e0);
            if (!is_array($epr)) {
                $epr = array();
            }
            $epr[ 0 ] = 1; $epr[ 1 ] = 2;
            
            /* search for best Knuth-Schroepel multiplier */
            do {
                $kk = Main::$pk[ ++$nk ];
                
                if ($kk == 0) {                         // finished
                    $kk = Main::$pk[ $bk ];
                    $bFound = true;
                }
                $D = gmp_mul(gmp_init($kk), gmp_init($N));
                $fks = log(2.0e0) / (2.0e0);
                $rem = gmp_intval( gmp_mod($D, gmp_init("8")));
                
                if ($rem == 1) $fks *= (4.0e0);
                if ($rem == 5) $fks *= (2.0e0);
                $fks -= log((double) $kk) / (2.0e0);
                
                $i = 0;
                $j = 1;
                while ( $j < $mm ) {
                    /* select small primes */
                    $p = 0;
                }
            } while ( !$bFound );
            return $kk;
        }

        public static function factor($arg, $bVerbose) {

            $return = array();
            $n = $arg;
            while (gmp_cmp($n, "1") > 0) {
                //$f = self::getBrent($n);
                $f = self::quadraticSieve($n, $bVerbose);
                #$tf = gmp_strval($f);
                if (gmp_cmp($f, "1") == 0) { break; }
                //if (self::isMillerRabinPrime($f)) {
                if (self::isFermatPrime($f)) {
                    $return[] = gmp_strval($f);
                    $n = gmp_div($n, $f);
                }
            }
            sort($return);
            return $return;
        }
        
        public static function swapMatrix($i, $p, &$matrix) {
            
            if (!is_array($matrix)) { return; }
            if ($i == $p) { return; }
            for ($j=0;$j < count($matrix[ 0 ]);$j++) {
                $tmp[ 0 ][ $j ] = $matrix[ $i ][ $j ];
                $matrix[ $i ][ $j ] = $matrix[ $p ][ $j ];
                $matrix[ $p ][ $j ] = $tmp[ 0 ][ $j ];
            }
        }
        
        public static function quadraticSieve($arg, $bVerbose) {
            
            // 因数分解される数
            $gTarget = $arg;
            
            // 素因数準備
            $cnt = 0;
            $primeFactors = array_merge(array("-1"), self::$primes);
            $checkPrimes = array();
            
            // ふるいの幅の分だけ素数を設定するが、平方剰余の関係にあるもののみを設定
            for ($j=0;$j < count($primeFactors);$j++) {
                $prime = $primeFactors[ $j ];
                if ($prime > 2) {
                    $legendre = gmp_intval(self::gmp_legendre($gTarget, $prime));
                    /*
                    if ($bVerbose) {
                        echo("legendre:(". gmp_strval($gTarget) . "/" . gmp_strval($prime) ."):" . $legendre . " \n");
                    }
                     */
                    if ($legendre == 1) {
                        $checkPrimes[] = $prime;
                        $cnt++;
                        if ($cnt >= self::$sieveWidth) {
                            break;
                        }
                    }
                    else if ($legendre == 0) {
                        // 割り切れてしまった！
                        return $prime;
                    }
                }
                else {
                    $cnt++;
                    $checkPrimes[] = $prime;
                    if ($cnt >= self::$sieveWidth) {
                        break;
                    }
                }
            }
            if ($bVerbose) {
                self::print_matrix($checkPrimes, "Check Primes : ");
            }
            
            // x2 - n ≡ 0 (mod Target)
            // ふるいの準備
            $gRootTarget = gmp_sqrt($gTarget);
            if ($bVerbose) {
                echo "Target root: " . gmp_strval($gRootTarget) . "\n";
            }
            
            $gBaseFrom = gmp_sub($gRootTarget, self::$sieveBound);
            $gBaseTo = gmp_add($gRootTarget, self::$sieveBound);
            if ($bVerbose) {
                print "Base from: " . gmp_strval($gBaseFrom) . "/";
                print "Base to: " . gmp_strval($gBaseTo) . "\n";
            }
            
            $baseArray = array();
            $fxArray = array();
            for($gI = $gBaseFrom;gmp_cmp($gI, $gBaseTo) <= 0;$gI = gmp_add($gI, 1)) {
                $baseArray[] = gmp_strval($gI);
                $powI = gmp_mul($gI, $gI);
                $fxArray[ gmp_strval($gI) ] = gmp_strval(gmp_sub($powI, $gTarget));
            }
            
            if ($bVerbose) {
                self::print_matrix($fxArray, "f(x) array : ");
            }
            
            // 準備した素数で割る：行列生成
            $originFxArray = $fxArray;
            $originMatrix = array();
            foreach ($fxArray as $key => $val) {
                
                $fx = $fxArray[ $key ];
                
                // 行初期化
                $newArray = array_fill(0, count($checkPrimes), 0);
                for ($i=0;$i < count($checkPrimes);$i++) {
                        
                    $checkPrime = $checkPrimes[ $i ];
                    if ($i == 0) {
                        if (gmp_cmp($fx, 0) < 0) {
                            $newArray[ $i ] = 1;
                            $fxArray[ $key ] = gmp_strval(gmp_div($fx, -1));
                        }
                    }
                    else {
                        while (gmp_cmp(gmp_mod($fxArray[ $key ], $checkPrime), 0) == 0) {
                            $newArray[ $i ]++;
                            $fxArray[ $key ] = gmp_strval(gmp_div($fxArray[ $key ], $checkPrime));
                        }
                    }
                }
                $originMatrix[ $key ] = $newArray;
            }
            if ($bVerbose) {
                self::print_matrix($originMatrix, "Origin Matrix : ");
            }
            
            // fx の残りが 1 以外は除外
            $bitMatrix = array();
            $checkXArray = array();
            foreach ($originMatrix as $key => $val) {
                if (is_array($val)) {
                    if (gmp_cmp($fxArray[ $key ], 1) == 0) {
                        $bitMatrix[] = $originMatrix[ $key ];
                        $checkXArray[] = $key;
                    }
                }
            }
            if ($bVerbose) {
                self::print_matrix($checkXArray, "check X Array : ");
            }
            
            // 行列の数値を偶奇にセット
            foreach ($bitMatrix as $key => $line) {
                foreach ($line as $i => $val) {
                    if (gmp_cmp(gmp_mod($val, 2), 0) == 0) {
                        $bitMatrix[ $key ][ $i ] = 0;
                    }
                    else {
                        $bitMatrix[ $key ][ $i ] = 1;
                    }
                }
            }
            
            // 単位行列を生成
            $unitMatrix = array();
            $cntChkPrimes = count($checkPrimes);
            $cntMatrix = count($bitMatrix);
            for($i=0;$i < $cntMatrix;$i++) {
                for($j=0;$j < $cntMatrix;$j++) {
                    if ($i == $j) {
                        $unitMatrix[$i][$j] = 1;
                    }
                    else {
                        $unitMatrix[$i][$j] = 0;
                    }
                }
            }
            //self::print_matrix($unitMatrix,"Unit Matrix");
            
            // 単位行列を連結
            $cnt = 0;
            foreach ($bitMatrix as $key => $line) {
                $bitMatrix[ $key ] = array_merge($line, $unitMatrix[ $cnt++ ]);
            }
            // 連結後行列サイズ
            $cntConMatrix = count($bitMatrix[ 0 ]);
            self::print_matrix($bitMatrix, "Bit Matrix");
            
            // 前進消去
            for ($i=0;$i < $cntMatrix;$i++) {
                
                // 対角要素が 0 なら pivoting 
                if ($bitMatrix[ $i ][ $i ] == 0) {
                    for ($j=$i+1;$j < $cntMatrix;$j++) {
                        if ($bitMatrix[ $j ][ $i ] != 0) {
                            self::swapMatrix($i, $j, $bitMatrix);
                            break;
                        }
                    }
                }
                
                // 割り算する係数を設定
                $divide = $bitMatrix[ $i ][ $i ];
                if ($divide == 0) { continue; }
                
                // 標準化 (divide は必ず 1 のため必要なし)
                /*
                for ($j=0;$j < $cntMatrix;$j++) {
                    $bitMatrix[ $i ][ $j ] /= $divide;
                }
                 */
                
                // i行目以外の行から引く(Galoa Field 2)
                for ($k=$i+1;$k < $cntMatrix;$k++) {
                    
                    if ($bitMatrix[ $k ][ $i ] == 0) { continue; }
                    for ($j=0;$j < $cntConMatrix;$j++) {
                        $bitMatrix[ $k ][ $j ] ^= $bitMatrix[ $i ][ $j ];
                    }
                }
               //self::print_matrix($bitMatrix, "Elimination : " . $i);
            }
            if ($bVerbose) {
                self::print_matrix($bitMatrix, "Elimination [GF(2)] : " . $i);
            }
            
            // 合同式で使える組み合わせを抽出
            $cngMatrix = array();
            for ($i=0;$i < $cntMatrix;$i++) {
                for ($j=0;$j < $cntConMatrix;$j++) {
                    if ($j < $cntChkPrimes) {
                        if ($bitMatrix[ $i ][ $j ] != 0) {
                            continue 2;
                        }
                    }
                    else {
                        $cngMatrix[] = array_slice($bitMatrix[ $i ], $cntMatrix-1);
                        continue 2;
                    }
                }
            }
            if ($bVerbose) {
                self::print_matrix($cngMatrix, "Congruence Matrix : ");
            }
            
            // x のリスト取得
            $retXMatrix = array();
            for ($i=0;$i < count($cngMatrix); $i++) {
                $retXMatrix[ $i ] = array();
                for ($j=0;$j < count($cngMatrix[ 0 ]);$j++) { 
                    if ($cngMatrix[ $i ][ $j ] == 1) {
                        $retXMatrix[ $i ][] = $checkXArray[ $j ];
                    }
                }
            }
            if ($bVerbose) {
                self::print_matrix($retXMatrix, "Return X Matrix : ");
            }
            
            // x に対する合同(mod Target)を取得
            $primesBitMatrix = array();
            for ($i=0;$i < count($retXMatrix);$i++) {
                $primesBitMatrix[ $i ] = array();
                for ($j=0;$j < count($retXMatrix[ $i ]);$j++) {
                    
                    $key = $retXMatrix[ $i ][ $j ];
                    $getPrimesBitArray = $originMatrix[ (string)$key ];
                    
                    if (count($primesBitMatrix[ $i ]) <= 0) {
                        $primesBitMatrix[ $i ] = $getPrimesBitArray;
                    }
                    else {
                        for ($k=0;$k < count($primesBitMatrix[ $i ]);$k++) {
                            $primesBitMatrix[ $i ][ $k ] += $getPrimesBitArray[ $k ];
                        }
                    }
                }
            }
            if ($bVerbose) {
                self::print_matrix($primesBitMatrix, "Primes Bit Matrix : ");
            }
            
            // retX, primesBitMatrix の数値化
            $retXArray = array();
            $primesCongruences = array();
            for ($i=0;$i < count($retXMatrix);$i++) {
                $tempX = gmp_init(1);
                for ($j=0;$j < count($retXMatrix[ $i ]);$j++) {
                    $tempX = gmp_mul($tempX, gmp_init($retXMatrix[ $i ][ $j ]));
                }
                $retXArray[] = gmp_strval($tempX);
                
                $tempX = gmp_init(1);
                for($j=1;$j < count($primesBitMatrix[ $i ]);$j++) { // j == 0 は -1 なので考慮しない
                    $bit = $primesBitMatrix[ $i ][ $j ] / 2;
                    if ($bit != 0) {
                        $tempX = gmp_mul($tempX, gmp_pow($checkPrimes[ $j ], $bit));
                    }
                }
                $primesCongruences[] = gmp_strval($tempX);
            }
            if ($bVerbose) {
                self::print_matrix($retXArray, "Return X Array : ");
                self::print_matrix($primesCongruences, "Primes Congruences : ");
            }
            
            // GCD 取得により素因数取得
            $dividers = array();
            for ($i=0;$i < count($retXArray);$i++) {
                $sub = gmp_sub($retXArray[ $i ], $primesCongruences[ $i ]);
                
                // 自明解は除去
                if (gmp_cmp($sub, 0) == 0) { continue; }
                if (gmp_cmp($sub, $gTarget) == 0) { continue; }
                $dividers[] = gmp_strval(gmp_gcd($sub, $gTarget));
            }
            if ($bVerbose) {
                self::print_matrix($dividers, "Found dividers : ");
            }
            $retVal = $arg;
            if (count($dividers) > 0) {
                $retVal = array_shift($dividers);
            }
            return $retVal;
        }
        
        /**
         * Project euler problem 3
         */
        public function main($args=null)
        {
            $strProblem = "13195 の素因数は 5、7、13、29 である。";
            $strProblem .= "13195 の素因数分解を求めよ。";

            $arg = gmp_init("13195");
            $result = "";
            if (is_array($args) && array_key_exists(0, $args)) {
                list($arg) = $args;
            }

            $n = $arg;
            $result = gmp_strval($n) . " = ";
            $sortArray = self::factor($n, true);
            $result .= implode(" * ", $sortArray);

            return $result;
        }

    }
    $time_start = microtime(true);

    $main = new Main();
    echo "result: " . $main->main() . "\n";
    //echo "Next Prime: " . gmp_strval(Main::getNextPrime(gmp_init("1000000000000000000000000000000000000000000000000000000000000"))) . "\n";

    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "time elapsed: " . $time . " sec.";
?>