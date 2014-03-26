<?php
    class Main {

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

        public static function isMillerRabinPass($n, $t=20) {

            if (gmp_cmp($n, "2") < 0) return false;
            if (gmp_cmp($n, "2") == 0) return true;
            if (!gmp_and($n, "1")) return false;
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
                if (self::isMillerRabinPass($f)) {
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
            $strProblem .= "600851475143 の素因数のうち最大のものを求めよ。";

            $arg = gmp_init("354584554504585");
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
    $main = new Main();
    echo "result: " . $main->main();
?>