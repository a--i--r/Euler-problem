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
991 997";

        public static $checkStart = 1008;

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

        /**
         * Returns gratest common divisor between two numbers
         */
        public static function getGCD($a,$b) {

            $a = gmp_abs($a);
            $b = gmp_abs($b);
            if (gmp_cmp($b, "0") == 0) {
                return $a;
            }

            return self::getGCD($b, gmp_mod($a,$b));
        }

        public static function mathPowMod($base, $power, $mod) {
            $result = gmp_init("1");
            while (gmp_cmp($power, "0") > 0) {
                if (gmp_strval(gmp_and($power, "1")) == "1") {
                    $result = gmp_mod(gmp_mul($result, $base), $mod);
                }
                $base = gmp_mod(gmp_mul($base, $base), $mod);
                $power = gmp_sub($power, "1");
            }
            return $result;
        }

        public static function isMillerRabinPrime($n, $t=20) {

            if (gmp_cmp($n, "2") < 0) return false;
            if (gmp_cmp($n, "2") == 0) return true;
            if (!gmp_and($n, "1")) return false;

            /* 既知の素数チェック */
            $ret = self::isPreparedPrime($n);
            if (is_bool($ret)) { return $ret; }

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

        public static function isFermatPrime($n) {

            if (gmp_cmp($n, "2") < 0) return false;
            if (gmp_cmp($n, "2") == 0) return true;
            if (!gmp_and($n, "1")) return false;

            /* 既知の素数チェック */
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

        public static function getBrent($n) {

            if (gmp_cmp(gmp_mod($n, "2"), "0") == 0) { return 2; }

            /* 既知の素数チェック */
            $ret = self::getPreparedFactor($n);
            if ($ret !== null) { return $ret; }

            /* 既知でないものは自力で探す */
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

        public static function factor($arg) {

            $return = array();
            $n = $arg;
            while (gmp_cmp($n, "1") > 0) {
                $f = self::getBrent($n);
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

        /**
         * Project euler problem 3
         */
        public function main($args=null)
        {
            $strProblem = "13195 の素因数は 5、7、13、29 である。";
            $strProblem .= "1962354882291932713 の素因数分解を求めよ。";

            $arg = gmp_init("1962354882291932713");
            $result = "";
            if (is_array($args) && array_key_exists(0, $args)) {
                list($arg) = $args;
            }

            $n = $arg;
            $result = gmp_strval($n) . " = ";
            $sortArray = self::factor($n);
            $result .= implode(" * ", $sortArray);

            return $result;
        }

    }
    $time_start = microtime(true);

    $main = new Main();
    echo "result: " . $main->main();

    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "time elapsed: " . $time . " sec.";
?>