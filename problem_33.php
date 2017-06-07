<?php
class Main {
        
    public function makeHash() {

        $ret = 0;
        $base = "answer";
        $aryPandigital = array();

        // 計算
        $denMul = 1;
        $nomMul = 1;
        
        for ($i=1;$i < 10;$i++) {
            for ($den=1;$den < $i;$den++) {
                for ($nom=1;$nom < $den;$nom++) {
                    if (($nom * 10 + $i) * $den == $nom * ($i * 10 + $den)) {
                        $denMul *= $den;
                        $nomMul *= $nom;
                    }
                }
            }
        }
        $gcdDen = gmp_strval(gmp_gcd(gmp_init($denMul), gmp_init($nomMul)));
        $base = "GCD:$gcdDen";
        $ret = $denMul / $gcdDen;
      
        $this->hashMap[ "${base}" ] = $ret;
        // ソート
        uasort($this->hashMap, "gmp_cmp");
    }
        
    /**
     * Project euler problem
     */
    public function execute($args=null)
    {
        $this->strProblem = "49/98は面白い分数である. \n\n";
        $strPRB = <<< PRB
「分子と分母からそれぞれ9を取り除くと, 49/98 = 4/8 となり, 簡単な形にすることができる」と経験の浅い数学者が誤って思い込んでしまうかもしれないからである.

(方法は正しくないが，49/98の場合にはたまたま正しい約分になってしまう．)

我々は 30/50 = 3/5 のようなタイプは自明な例だとする.

このような分数のうち, 1より小さく分子・分母がともに2桁の数になるような自明でないものは, 4個ある.

その4個の分数の積が約分された形で与えられたとき, 分母の値を答えよ.
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

