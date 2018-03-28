<?php
ini_set('memory_limit', '6000M');

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
        public static $pk = array( 0,1,2,3,5,6,7,10,11,13,14,15,17,0 );
        public static $checkStart = 212;
        public static $maxInt = 2147483647;
        
        // d のゆらぎ
        public static $dWidth = 50;
        
        // 篩の素数幅
        public static $sieveWidth = 35;
        // 篩の大きさ
        public static $sieveBound = 10000;
        
        // 篩
        public static $bitMatrix = array();
        public static $selectedMatrix = array();

        public function __construct() {

            self::$primesString = preg_replace("@\n|\r|\r\n|\s+@", " ", self::$primesString);
            self::$primes = split(" ", self::$primesString);
            self::$primesLength = count(self::$primes);
        }

        public static function print_matrix($matrix, $name) {
            
            if (!is_array($matrix)) { return; }
            echo "----- ${name} -----\n";

            $notMatrix = false;
            foreach ($matrix as $row) {
                
                if (is_array($row)) {
                    foreach ($row as $col) {
                        printf("%2s ", $col);
                    }
                    echo "\n";
                }
                else {
                    $notMatrix = true;
                    printf("%2s ", $row);
                }
            }
            if ($notMatrix) {
                echo "\n";
            }
        }
    
        public static function hasArrayInMatrix($matrix, $ary) {
            
            if (!is_array($matrix)) { return false; }
            if (!is_array($ary)) { return false; }
            
            foreach ($matrix as $row) {
                
                if (is_array($row)) {
                    if ($row === $ary) {
                        return true;
                    }
                }
                else {
                    if ($matrix === $ary) {
                        return true;
                    }
                }
            }
            return false;
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

        public static function factorBrent($a, $b, $c) {
            return gmp_mod(gmp_add(gmp_mod(gmp_mul($a, $a), $b), $c), $b);
        }

        public static function gmp_min($a, $b) {
            if (gmp_cmp($a, $b) <= 0) {
                return $a;
            }
            return $b;
        }
        
        public static function gmp_nthroot($target, $n) {
            
            if (gmp_cmp($target, "0") <= 0 || gmp_cmp($n, "0") < 0) {
                return "0";
            }
            if (gmp_cmp($n, "1") == 0) { return "1"; }
            if (gmp_cmp($target, "1") <= 0) { return "1"; }

            $y = $target;
            do {
                $x = gmp_strval($y);
                $y = gmp_strval(gmp_div(gmp_add(gmp_mul(gmp_sub($n, 1), $x),  gmp_div($target, gmp_pow($x, gmp_strval(gmp_sub($n, 1))))), $n));
            } while ( gmp_cmp($y, $x) < 0 );
            return gmp_strval($y);
        }
           
        public static function gmp_is_power($target) {
            
            if (gmp_cmp($target, 1) == 0) { return "1"; }
            $target = gmp_strval($target);
            
            // log2($target) = log10($target) / log10(2)
            $length = strlen($target) - 1;
            $log2Target = (integer)floor($length / log10(2));
            
            for ($i="2";gmp_cmp($i, $log2Target) <= 0;$i = gmp_strval(gmp_add($i, 1))) {
                $nthRoot = gmp_strval(self::gmp_nthroot($target, $i));
                $pow = gmp_strval(gmp_pow($nthRoot, $i));
                if (gmp_cmp($pow, $target) == 0) {
                    return $nthRoot;
                }
            }
            return false;
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
           $sortArray = $a;
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

        public static function swapMatrix($i, $p, &$matrix) {
            
            if (!is_array($matrix)) { return; }
            if ($i == $p) { return; }
            for ($j=0;$j < count($matrix[ 0 ]);$j++) {
                $tmp[ 0 ][ $j ] = $matrix[ $i ][ $j ];
                $matrix[ $i ][ $j ] = $matrix[ $p ][ $j ];
                $matrix[ $p ][ $j ] = $tmp[ 0 ][ $j ];
            }
        }
        
        public static function selfInitializedQuadraticSieve($arg, $bVerbose) {
            
            // 因数分解される数
            $gTarget = $arg;
            $strTarget = gmp_strval($gTarget);
            if (strlen($strTarget) <= 0) { return ""; }
            
            if ($bVerbose) {
                echo "----- Execute multiple polynomial quadratic sieve -----\n";
                echo "Target number : " . $strTarget . "\n";
            }
            
            // 準備した primes で試しに割る
            for ($i=0;$i < count(self::$primes);$i++) {
                if (gmp_cmp(gmp_mod($gTarget, self::$primes[ $i ]), 0) == 0) {
                    if ($bVerbose) {
                        echo "Divided by prepared primes : " . self::$primes[ $i ] . "\n";
                    }
                    return self::$primes[ $i ];
                }
            }
            
            // べき乗数チェック
            $nthRoot = self::gmp_is_power($gTarget);
            if ($nthRoot !== false) {
                if ($bVerbose) {
                    echo "Divided by nth root prime : " . $nthRoot . "\n";
                }
                return $nthRoot;
            }
            
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
                        if ($bVerbose) {
                            echo "Divided by a prime factor : " . $prime . "\n";
                        }
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
            $cntChkPrimes = count($checkPrimes);
            
            // F(x) = (Ax + b)2 - N = A * f(x)
            // f(x) = A * x2 + B * x + C
            // B = 2b
            // b2 - N ≡ 0 (mod A)
            // C = F(0) / A
            // s = F(0) (mod A)
            // A を素数基底から選ぶ
            $A = 0;
            $B = 0;
            $C = 0;
            $retVal = array();
            self::$bitMatrix = array();
            self::$selectedMatrix = array();
            $powArray = array();
            $cnt = 0;
            for ($i=0;$i < count($checkPrimes);$i++) {
                $p = $checkPrimes[ $i ];
                if ($p < 7) { continue; }
                for($j=1;$j < self::$dWidth;$j+=2) {
                    $fz = gmp_strval(gmp_sub(gmp_pow($j, 2), $gTarget));
                    $s = gmp_intval(gmp_mod($fz, $p));
                    if ($s == 0) {
                        $A = $p;
                        $b = $j;
                        $B = 2 * $b;
                        $C = gmp_strval(gmp_div($fz, $A));
                        if ($bVerbose) {
                            echo "f(0) : " . $fz . "/";
                            echo "b : " . $b . "/";
                            echo "A : " . $A . "/";
                            echo "B : " . $B . "/";
                            echo "C : " . $C . "\n";
                        }
                        
                        self::prepareSeive($A, $B, $C, $b, $gTarget, $checkPrimes, $powArray, $bVerbose);
                        if (count(self::$bitMatrix) <= count($checkPrimes)) {
                            continue;
                        }
                        else if ($cnt > 0 && count(self::$bitMatrix) > count($checkPrimes)) {
                            $nChkPrimes = count($checkPrimes);
                            self::$bitMatrix = array_slice(self::$bitMatrix, -$nChkPrimes);
                            self::$selectedMatrix = array_slice(self::$bitMatrix, -$nChkPrimes);
                            $powArray = array_slice($powArray, -$nChkPrimes);
                        }
                        
                        $retVal = self::calcBitMatrix($powArray, $gTarget, $checkPrimes, $bVerbose);
                        $cnt++;
                        foreach($retVal as $key => $val) {
                            if (gmp_cmp($val, $gTarget) != 0 && gmp_cmp($val, 1) != 0) {
                                break 3;
                            }
                        }
                    }
                }
            }
            if ($A == 0) {
                // 見つかりませんでした
                if ($bVerbose) {
                    echo "Cannot get a prime factor (not found A) : " . $strTarget . "\n";
                }
                return array($strTarget);
            }
            if (count($retVal) <= 0) {
                // 見つかりませんでした
                if ($bVerbose) {
                    echo "Cannot get a prime factor (not found retVal) : " . $strTarget . "\n";
                }
                return array($strTarget);
            }
            return $retVal;
        }
        
        // 篩生成
        private static function prepareSeive($A, $B, $C, $b, $gTarget, $checkPrimes, &$powArray, $bVerbose) {
            
            if (!is_array($checkPrimes) || count($checkPrimes) <= 1) {
                return false;
            }
            
            // x を sieveBound の間ループして
            // f(x) と素数基底で 篩生成
            $originMatrix = array();
            //$originPrintMatrix = array();
            $totalArray = array();
            //self::$selectedMatrix = array();
            //$selectedPrintMatrix = array();
            //self::$bitMatrix = array();
            //$powArray = array();        // Ax + b
            for ($x=-self::$sieveBound, $i=0;$x <= self::$sieveBound;$x++,$i++) {
                
                // calc f(x)
                $fx = gmp_strval(gmp_add(gmp_add(gmp_mul($A, gmp_pow($x, 2)), gmp_mul($B, $x)), $C));
                if ($bVerbose) {
                    //echo "f(${x}) : " . $fx . "\n";
                    //$originPrintMatrix[ $i ][] = sprintf("f(%3s) : %15s", $x, $fx);
                }
                // setup special number "-1"
                if (gmp_cmp($fx, 0) < 0) {
                    $originMatrix[ $i ][ 0 ] = "1";
                    $totalArray[ $i ] = "-1";
                }
                else {
                    $originMatrix[ $i ][ 0 ] = "0";
                    $totalArray[ $i ] = "1";
                }
                //$originPrintMatrix[ $i ][] = $originMatrix[ $i ][ 0 ];
                
                // -1 以外は計算
                for ($j=1;$j < count($checkPrimes);$j++) {
                    $nDivided = "0";
                    $tfx = $fx;
                    
                    $divider = $checkPrimes[ $j ];
                    // 素数基底から選んだ A は自動的に1回分含む
                    if (gmp_cmp($A, $divider) == 0) {
                        $nDivided++;
                    }
                    
                    while (gmp_cmp(gmp_mod($tfx, $divider), 0) == 0) {
                        $nDivided = gmp_strval(gmp_add($nDivided, 1));
                        $tfx = gmp_div($tfx, $divider);
                        $totalArray[ $i ] = gmp_mul($totalArray[ $i ], $divider);
                        if (gmp_cmp($tfx, 1) == 0) {
                            break;
                        }
                    }
                    $originMatrix[ $i ][ $j ] = $nDivided;
                    //$originPrintMatrix[ $i ][] = $nDivided;
                }
                //$originPrintMatrix[ $i ][] = sprintf("%15s", gmp_strval($totalArray[ $i ]));
                
                // 設定した素数基底の乗算で表されるもののみ抽出
                $bitArray = array();
                if (gmp_cmp($fx, $totalArray[ $i ]) == 0) {
                    self::$selectedMatrix[] = $originMatrix[ $i ];
                    //$selectedPrintMatrix[] = $originPrintMatrix[ $i ];
                    $pow = gmp_strval(gmp_abs(gmp_add(gmp_mul($A, $x), $b)));
                    
                    // bitMatrix 生成
                    // 抽出した行列を GF(2) に変換する
                    for ($j=0;$j < count($originMatrix[ $i ]);$j++) {
                        if (gmp_mod($originMatrix[ $i ][ $j ], 2) == 0) {
                            $bitArray[ $j ] = 0;
                        }
                        else {
                            $bitArray[ $j ] = 1;
                        }
                    }
                    
                    // 既に同じものが無ければ追加
                    if (self::hasArrayInMatrix(self::$bitMatrix, $bitArray)) {
                        continue;
                    }
                    self::$bitMatrix[] = $bitArray;
                    $powArray[] = $pow;
                }
            }
            if ($bVerbose) {
                //self::print_matrix($originPrintMatrix, "Original Matrix :");
                //self::print_matrix($selectedPrintMatrix, "Selected Matrix :");
                self::print_matrix(self::$bitMatrix, "Bit Matrix :");
                self::print_matrix($powArray, "Pow Array :");
            }
            if (count(self::$bitMatrix) <= 0) {
                // 見つからない
                if ($bVerbose) {
                    echo "Cannot get a prime factor (not found bit matrix) : " . gmp_strval($gTarget) . "\n";
                }
                return false;
            }
            return true;
        }
        
        // 行列計算
        private static function calcBitMatrix(&$powArray, $gTarget, $checkPrimes, $bVerbose) {
            
            // 単位行列を生成
            $unitMatrix = array();
            $cntMatrix = count(self::$bitMatrix);
            $localBitMatrix = self::$bitMatrix;
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
            // 単位行列を連結
            $cnt = 0;
            //$printBitMatrix = array();
            $selectedPowArray = array();
            foreach ($localBitMatrix as $key => $line) {
                $localBitMatrix[ $key ] = array_merge($line, $unitMatrix[ $cnt++ ]);
                //$printBitMatrix[ $key ] = $localBitMatrix[ $key ];
                //$printBitMatrix[ $key ][] = $powArray[ $key ];
                $selectedPowArray[] = $powArray[ $key ];
            }
            // 連結後行列サイズ
            $cntConMatrix = count($localBitMatrix[ 0 ]);
            //self::print_matrix($printBitMatrix, "Combined Bit Matrix");
            
            // ガウス消去
            for ($i=0;$i < $cntMatrix;$i++) {
                
                // 対角要素が 0 なら pivoting 
                if ($localBitMatrix[ $i ][ $i ] == 0) {
                    for ($j=$i+1;$j < $cntMatrix;$j++) {
                        if ($localBitMatrix[ $j ][ $i ] != 0) {
                            self::swapMatrix($i, $j, $localBitMatrix);
                            break;
                        }
                    }
                }
                
                // 割り算する係数を設定
                if ($localBitMatrix[ $i ][ $i ] == 0) { continue; }
                
                // i+1行目の行から引く(Galoa Field 2)
                for ($k=$i+1;$k < $cntMatrix;$k++) {
                    
                    if ($localBitMatrix[ $k ][ $i ] == 0) { continue; }
                    for ($j=0;$j < $cntConMatrix;$j++) {
                        // 各項目の xor を取る
                        $localBitMatrix[ $k ][ $j ] ^= $localBitMatrix[ $i ][ $j ];
                    }
                }
                /*
                if ($bVerbose) {
                    self::print_matrix($localBitMatrix, "Elimination [GF(2)] : " . $i);
                }
                exit(-1);
                 */
            }
            if ($bVerbose) {
                //self::print_matrix($localBitMatrix, "Elimination [GF(2)] : " . $i);
            }
            
            // 合同式で使える組み合わせを抽出
            $cngMatrix = array();
            for ($i=0;$i < $cntMatrix;$i++) {
                for ($j=0;$j < $cntConMatrix;$j++) {
                    if ($j < count($checkPrimes)) {
                        if ($localBitMatrix[ $i ][ $j ] != 0) {
                            continue 2;
                        }
                    }
                    else {
                        $cngMatrix[] = array_slice($localBitMatrix[ $i ], count($checkPrimes));
                        continue 2;
                    }
                }
            }
            // 素因数分解できない
            if (count($cngMatrix) <= 0) {
                if ($bVerbose) {
                    echo "Cannot get congruence matrix (may be prime) : " . gmp_strval($gTarget) . "\n"; 
                }
                return array(gmp_strval($gTarget));
            }
            if ($bVerbose) {
                self::print_matrix($cngMatrix, "Congruence Matrix : ");
            }

            // 計算準備（行列から数値に変換）
            $QxArray = array();
            $QxModComp= array();
            $QxComposite = array();
            $QxPrimesCounts = array();
            $QxDiv2PC = array();
            $QxPrimes = array();
            $QxSquarePrimeComposite = array();
            $QxModSPC = array();
            //$printQxSPF = array();
            //$printQx = array();
            //$printSquareFormula = array();
            // Q(x) = (A*x+b)2 = Af(x)[mod N]
            for ($i=0;$i < count($cngMatrix);$i++) {
            
                $QxArray[ $i ] = array();
                $QxPrimesCounts[ $i ] = array();
                $QxDiv2PC[ $i ] = array();
                $QxComposite[ $i ] = 1;
                for ($j=0;$j < count($cngMatrix[ $i ]);$j++) {
                    if ($cngMatrix[ $i ][ $j ] == 1) {
                        
                        $QxArray[ $i ][] = gmp_strval($selectedPowArray[ $j ]);
                        $QxComposite[ $i ] = gmp_strval(gmp_mul($QxComposite[ $i ], $selectedPowArray[ $j ]));
                        $QxModComp[ $i ] = gmp_strval(gmp_mod($QxComposite[ $i ], $gTarget));
                        if (count($QxPrimesCounts[ $i ]) <= 0) {
                            $QxPrimesCounts[ $i ] = array_fill(0, count(self::$selectedMatrix[ $i ]), 0);
                        }
                        
                        for ($k=1;$k < count(self::$selectedMatrix[ $j ]);$k++) {
                            
                            $powPrime = self::$selectedMatrix[ $j ][ $k ];
                            $QxPrimesCounts[ $i ][ $k ] += $powPrime;
                        }
                    }
                }
                //$printQx[ $i ] = $QxArray[ $i ];
                //$printQx[ $i ][] = " = " . $QxComposite[ $i ] . " = " . $QxModComp[ $i ] . " (mod " . gmp_strval($gTarget) . ")";
                    
                // 素数の乗数 / 2
                $QxSquarePrimeComposite[ $i ] = 1;
                //$printQxSPF[ $i ] = array();
                for ($k=0;$k < count($QxPrimesCounts[ $i ]);$k++) {
                    if (gmp_cmp($QxPrimesCounts[ $i ][ $k ], 1) > 0) {
                        $QxDiv2PC[ $i ][ $k ] = gmp_strval(gmp_div($QxPrimesCounts[ $i ][ $k ], 2));
                        $QxSquarePrimeComposite[ $i ] = gmp_strval(gmp_mul($QxSquarePrimeComposite[ $i ], gmp_pow($checkPrimes[ $k ], $QxDiv2PC[ $i ][ $k ])));
                        //$printQxSPF[ $i ][] = $checkPrimes[ $k ] . "**" . $QxDiv2PC[ $i ][  $k ];
                    }
                    else {
                        $QxDiv2PC[ $i ][ $k ] = $QxPrimesCounts[ $i ][ $k ];
                    }
                }
                $QxModSPC[ $i ] = gmp_strval(gmp_mod($QxSquarePrimeComposite[ $i ], $gTarget));
                //$printQxSPF[ $i ][] = " = " . $QxSquarePrimeComposite[ $i ] . " = " . $QxModSPC[ $i ] . " (mod " . gmp_strval($gTarget) . ")";
                //$printSquareFormula[ $i ][] = "(" . $QxModSPC[ $i ] . ")**2 = (" . $QxModComp[ $i ] . ")**2";
            }
            if ($bVerbose) {
                self::print_matrix($QxPrimesCounts, "QxPrimesCounts : ");
                self::print_matrix($QxDiv2PC, "QxDivided2PrimesCounts : ");
                //self::print_matrix($printQxSPF, "SquarePrimesFormula : ");
                //self::print_matrix($printQx, "Qx Formula : ");
                //self::print_matrix($printSquareFormula, "Congruence Square Formula :");
            }
            
            // GCD 計算により素因数取得
            $dividers = array();
            for ($i=0;$i < count($QxModComp);$i++) {
                
                $add = gmp_strval(gmp_add($QxModComp[ $i ], $QxModSPC[ $i ]));
                $sub = gmp_strval(gmp_abs(gmp_sub($QxModComp[ $i ], $QxModSPC[ $i ])));
                if (gmp_cmp($gTarget, $add) == 0 || gmp_cmp($gTarget, $sub) == 0) {
                    // 自明解でした
                    $add = 1; $sub = 1;
                }
                $gcdAdd = gmp_strval(gmp_gcd($add, $gTarget));
                $gcdSub = gmp_strval(gmp_gcd($sub, $gTarget));
                // 自明解でした
                if (gmp_cmp($gcdAdd, $gTarget) == 0 || gmp_cmp($gcdAdd, 1) == 0) {
                    ;
                }
                else {
                    // 既にあったら登録しない
                    if (!in_array($gcdAdd, $dividers)) {
                        $dividers[] = $gcdAdd;
                    }    
                }
                if (gmp_cmp($gcdSub, $gTarget) == 0 || gmp_cmp($gcdSub, 1) == 0) {
                    continue;
                }
                if (!in_array($gcdSub, $dividers)) {
                    $dividers[] = $gcdSub;
                }
            }

            if (count($dividers) <= 0) {
                // 自明解しか出ませんでした!
                if ($bVerbose) {
                    echo "Only get trivial solution : " . gmp_strval($gTarget) . "\n";
                }
            }
            
            // 結果をソート
            $retVal = self::gmp_sort($dividers);
            if ($bVerbose) {
                //self::print_matrix($gcdAddArray, "GCD add results :");
                //self::print_matrix($gcdSubArray, "GCD sub results :");
                if (count($retVal) > 0) {
                    self::print_matrix($retVal, "Solved dividers : ");
                }
            }
            return $retVal;
        }
        
        public static function factor($arg, $bVerbose) {

            $return = array();
            $n = $arg;
            
            // 最初に素数だったら終了
            if (self::isMillerRabinPrime($n)) {
                $return[] = gmp_strval($n);
                return $return;
            }
            
            // 素因数を探す
            while (gmp_cmp($n, "1") > 0) {
                $fs = self::selfInitializedQuadraticSieve($n, $bVerbose);
                if (!is_array($fs)) {
                    $fs = array($fs);
                }
                foreach($fs as $key => $val) {
                    if (gmp_cmp($val, "1") == 0)  { break 2; }

                    // 残念ながら解けなかった
                    if (gmp_cmp($val, $n) == 0)   { 
                        $return[] = gmp_strval($val);
                        $n = gmp_div($n, $val);
                        break 2;
                    }

                    if (self::isMillerRabinPrime($val)) {
                        $return[] = gmp_strval($val);
                        $n = gmp_div($n, $val);
                    }
                    if (self::isMillerRabinPrime($n)) {
                        // 終了
                        $return[] = gmp_strval($n);
                        break 2;
                    }
                }
            }
            self::gmp_sort($return);
            return $return;
        }
        
        /**
         * Project euler problem 3
         */
        public function execute($args=null)
        {
            $strProblem = "13195 の素因数は 5、7、13、29 である。";
            $arg = gmp_init("1565912117761");
            $arg = gmp_init("1565912117761");
            
            $result = "";
            if (is_array($args) && array_key_exists(0, $args)) {
                list($arg) = $args;
            }
            
            $strProblem .= gmp_strval($arg) . " の素因数分解を求めよ。";

            $n = $arg;
            $result = gmp_strval($n) . " = ";
            $sortArray = $this->factor($n, true);
            $result .= implode(" * ", $sortArray);

            return $result;
        }

    }
    
    $time_start = microtime(true);
    
    $main = new Main();
    echo "result: " . $main->execute() . "\n";
    //echo "Next Prime: " . gmp_strval(Main::getNextPrime(gmp_init("10000000000000"))) . "\n";

    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "time elapsed: " . $time . " sec.";
?>