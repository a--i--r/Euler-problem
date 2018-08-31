<?php
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
991 997 1009 1013 1019 1021 1031 1033 1039 1049 1051 1061 1063 1069 1087 
1091 1093 1097 1103 1109 1117 1123 1129 1151 1153 1163 1171 1181 1187 1193 
1201 1213 1217 1223 1229 1231 1237 1249 1259 1277 1279 1283 1289 1291 1297 
1301 1303 1307 1319 1321 1327 1361 1367 1373 1381 1399 1409 1423 1427 1429 
1433 1439 1447 1451 1453 1459 1471 1481 1483 1487 1489 1493 1499 1511 1523 
1531 1543 1549 1553 1559 1567 1571 1579 1583 1597 1601 1607 1609 1613 1619 
1621 1627 1637 1657 1663 1667 1669 1693 1697 1699 1709 1721 1723 1733 1741 
1747 1753 1759 1777 1783 1787 1789 1801 1811 1823 1831 1847 1861 1867 1871 
1873 1877 1879 1889 1901 1907 1913 1931 1933 1949 1951 1973 1979 1987 1993 
1997 1999 2003 2011 2017 2027 2029 2039 2053 2063 2069 2081 2083 2087 2089 
2099 2111 2113 2129 2131 2137 2141 2143 2153 2161 2179 2203 2207 2213 2221 
2237 2239 2243 2251 2267 2269 2273 2281 2287 2293 2297 2309 2311 2333 2339 
2341 2347 2351 2357 2371 2377 2381 2383 2389 2393 2399 2411 2417 2423 2437 
2441 2447 2459 2467 2473 2477 2503 2521 2531 2539 2543 2549 2551 2557 2579 
2591 2593 2609 2617 2621 2633 2647 2657 2659 2663 2671 2677 2683 2687 2689 
2693 2699 2707 2711 2713 2719 2729 2731 2741 2749 2753 2767 2777 2789 2791 
2797 2801 2803 2819 2833 2837 2843 2851 2857 2861 2879 2887 2897 2903 2909 
2917 2927 2939 2953 2957 2963 2969 2971 2999 3001 3011 3019 3023 3037 3041 
3049 3061 3067 3079 3083 3089 3109 3119 3121 3137 3163 3167 3169 3181 3187 
3191 3203 3209 3217 3221 3229 3251 3253 3257 3259 3271 3299 3301 3307 3313 
3319 3323 3329 3331 3343 3347 3359 3361 3371 3373 3389 3391 3407 3413 3433 
3449 3457 3461 3463 3467 3469 3491 3499 3511 3517 3527 3529 3533 3539 3541 
3547 3557 3559 3571 3581 3583 3593 3607 3613 3617 3623 3631 3637 3643 3659 
3671 3673 3677 3691 3697 3701 3709 3719 3727 3733 3739 3761 3767 3769 3779 
3793 3797 3803 3821 3823 3833 3847 3851 3853 3863 3877 3881 3889 3907 3911 
3917 3919 3923 3929 3931 3943 3947 3967 3989 4001 4003 4007 4013 4019 4021 
4027 4049 4051 4057 4073 4079 4091 4093 4099 4111 4127 4129 4133 4139 4153 
4157 4159 4177 4201 4211 4217 4219 4229 4231 4241 4243 4253 4259 4261 4271 
4273 4283 4289 4297 4327 4337 4339 4349 4357 4363 4373 4391 4397 4409 4421 
4423 4441 4447 4451 4457 4463 4481 4483 4493 4507 4513 4517 4519 4523 4547 
4549 4561 4567 4583 4591 4597 4603 4621 4637 4639 4643 4649 4651 4657 4663 
4673 4679 4691 4703 4721 4723 4729 4733 4751 4759 4783 4787 4789 4793 4799 
4801 4813 4817 4831 4861 4871 4877 4889 4903 4909 4919 4931 4933 4937 4943 
4951 4957 4967 4969 4973 4987 4993 4999 5003 5009 5011 5021 5023 5039 5051 
5059 5077 5081 5087 5099 5101 5107 5113 5119 5147 5153 5167 5171 5179 5189 
5197 5209 5227 5231 5233 5237 5261 5273 5279 5281 5297 5303 5309 5323 5333 
5347 5351 5381 5387 5393 5399 5407 5413 5417 5419 5431 5437 5441 5443 5449 
5471 5477 5479 5483 5501 5503 5507 5519 5521 5527 5531 5557 5563 5569 5573 
5581 5591 5623 5639 5641 5647 5651 5653 5657 5659 5669 5683 5689 5693 5701 
5711 5717 5737 5741 5743 5749 5779 5783 5791 5801 5807 5813 5821 5827 5839 
5843 5849 5851 5857 5861 5867 5869 5879 5881 5897 5903 5923 5927 5939 5953 
5981 5987 6007 6011 6029 6037 6043 6047 6053 6067 6073 6079 6089 6091 6101 
6113 6121 6131 6133 6143 6151 6163 6173 6197 6199 6203 6211 6217 6221 6229 
6247 6257 6263 6269 6271 6277 6287 6299 6301 6311 6317 6323 6329 6337 6343 
6353 6359 6361 6367 6373 6379 6389 6397 6421 6427 6449 6451 6469 6473 6481 
6491 6521 6529 6547 6551 6553 6563 6569 6571 6577 6581 6599 6607 6619 6637 
6653 6659 6661 6673 6679 6689 6691 6701 6703 6709 6719 6733 6737 6761 6763 
6779 6781 6791 6793 6803 6823 6827 6829 6833 6841 6857 6863 6869 6871 6883 
6899 6907 6911 6917 6947 6949 6959 6961 6967 6971 6977 6983 6991 6997 7001 
7013 7019 7027 7039 7043 7057 7069 7079 7103 7109 7121 7127 7129 7151 7159 
7177 7187 7193 7207 7211 7213 7219 7229 7237 7243 7247 7253 7283 7297 7307 
7309 7321 7331 7333 7349 7351 7369 7393 7411 7417 7433 7451 7457 7459 7477 
7481 7487 7489 7499 7507 7517 7523 7529 7537 7541 7547 7549 7559 7561 7573 
7577 7583 7589 7591 7603 7607 7621 7639 7643 7649 7669 7673 7681 7687 7691 
7699 7703 7717 7723 7727 7741 7753 7757 7759 7789 7793 7817 7823 7829 7841 
7853 7867 7873 7877 7879 7883 7901 7907 7919 7927 7933 7937 7949 7951 7963 
7993 8009 8011 8017 8039 8053 8059 8069 8081 8087 8089 8093 8101 8111 8117 
8123 8147 8161 8167 8171 8179 8191 8209 8219 8221 8231 8233 8237 8243 8263 
8269 8273 8287 8291 8293 8297 8311 8317 8329 8353 8363 8369 8377 8387 8389 
8419 8423 8429 8431 8443 8447 8461 8467 8501 8513 8521 8527 8537 8539 8543 
8563 8573 8581 8597 8599 8609 8623 8627 8629 8641 8647 8663 8669 8677 8681 
8689 8693 8699 8707 8713 8719 8731 8737 8741 8747 8753 8761 8779 8783 8803 
8807 8819 8821 8831 8837 8839 8849 8861 8863 8867 8887 8893 8923 8929 8933 
8941 8951 8963 8969 8971 8999 9001 9007 9011 9013 9029 9041 9043 9049 9059 
9067 9091 9103 9109 9127 9133 9137 9151 9157 9161 9173 9181 9187 9199 9203 
9209 9221 9227 9239 9241 9257 9277 9281 9283 9293 9311 9319 9323 9337 9341 
9343 9349 9371 9377 9391 9397 9403 9413 9419 9421 9431 9433 9437 9439 9461 
9463 9467 9473 9479 9491 9497 9511 9521 9533 9539 9547 9551 9587 9601 9613 
9619 9623 9629 9631 9643 9649 9661 9677 9679 9689 9697 9719 9721 9733 9739 
9743 9749 9767 9769 9781 9787 9791 9803 9811 9817 9829 9833 9839 9851 9857 
9859 9871 9883 9887 9901 9907 9923 9929 9931 9941 9949 9967 9973";
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
        
        // 篩の素数幅
        public static $sieveWidth = 12;
        // 篩の大きさ
        public static $sieveBound = 20000;

        public function __construct() {

            self::$primesString = preg_replace("@\n|\r|\r\n|\s+@", " ", self::$primesString);
            self::$primes = explode(" ", self::$primesString);
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
        public static function isFermatPrime($n) {

            if (gmp_cmp($n, "2") < 0) return false;
            if (gmp_cmp($n, "2") == 0) return true;
            if (gmp_cmp(gmp_and($n, "1"),"0") == 0) return false;

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
            $strTarget = gmp_strval($gTarget);
            if (strlen($strTarget) <= 0) { return ""; }
            
            if ($bVerbose) {
                echo "----- Execute quadratic sieve -----\n";
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
            
            // x2 - n ≡ 0 (mod Target)
            // ふるいの準備
            $gRootTarget = gmp_sqrt($gTarget);
            if ($bVerbose) {
                echo "Target root: " . gmp_strval($gRootTarget) . "\n";
            }
            
            $gBaseFrom = gmp_sub($gRootTarget, self::$sieveBound);
            if (gmp_cmp($gBaseFrom, 0) < 0) { $gBaseFrom = gmp_init(0); }
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
                
                // 割り切れる!
                if ($fxArray[ gmp_strval($gI) ] == 0) {
                    if ($bVerbose) {
                        echo "Divided by a square root : " . gmp_strval($gI) . "\n";
                    }
                    return gmp_strval($gI);
                }
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
                self::print_matrix($fxArray, "Remained F(x) : ");
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
            // 素因数分解できない！
            if (count($bitMatrix) <= 0) {
                if ($bVerbose) {
                    echo "Cannot get a prime factor by quadratic sieve : " . gmp_strval($gTarget) . "\n";
                }
                return gmp_strval($gTarget);
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
            
            // ガウス消去
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
                
                // i+1行目の行から引く(Galoa Field 2)
                for ($k=$i+1;$k < $cntMatrix;$k++) {
                    
                    if ($bitMatrix[ $k ][ $i ] == 0) { continue; }
                    for ($j=0;$j < $cntConMatrix;$j++) {
                        // 各項目の xor を取る
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
                        $cngMatrix[] = array_slice($bitMatrix[ $i ], $cntChkPrimes);
                        continue 2;
                    }
                }
            }
            // 素因数分解できない
            if (count($cngMatrix) <= 0) {
                if ($bVerbose) {
                    echo "Cannot get congruence matrix (may be prime) : " . gmp_strval($gTarget) . "\n"; 
                }
                return gmp_strval($gTarget);
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
                    $bit = gmp_strval(gmp_div($primesBitMatrix[ $i ][ $j ], 2));
                    if (gmp_cmp($bit, 0) != 0) {
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
                $divider = gmp_strval(gmp_gcd($sub, $gTarget));
                if (gmp_cmp($divider, $gTarget) == 0) { continue; }
                if (gmp_cmp($divider, 1) == 0) { continue; }
                $dividers[] = $divider;
            }
            if ($bVerbose) {
                self::print_matrix($dividers, "Found dividers : ");
            }
            $retVal = gmp_strval($gTarget);
            if (count($dividers) > 0) {
                $retVal = array_shift($dividers);
                if ($bVerbose) {
                    echo "Get solution : " . gmp_strval($retVal) . "\n";
                }
            }
            else {
                // 自明解しか出ませんでした!
                if ($bVerbose) {
                    echo "Only get trivial solution : " . $retVal . "\n";
                }
            }
            return $retVal;
        }
        
        public static function factor($arg, $bVerbose) {

            $return = array();
            $n = $arg;
            while (gmp_cmp($n, "1") > 0) {
                $f = self::quadraticSieve($n, $bVerbose);
                if (gmp_cmp($f, "1") == 0)  { break; }
                
                // 残念ながら解けなかった
                if (gmp_cmp($f, $n) == 0)   { 
                    $return[] = gmp_strval($f);
                    $n = gmp_div($n, $f);
                    break;
                }
                
                if (self::isFermatPrime($f)) {
                    $return[] = gmp_strval($f);
                    $n = gmp_div($n, $f);
                }
            }
            sort($return);
            return $return;
        }
        
        /**
         * Project euler problem 3
         */
        public function execute($args=null)
        {
            $strProblem = "13195 の素因数は 5、7、13、29 である。";
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