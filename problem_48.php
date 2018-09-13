<?php
ini_set('memory_limit', '2048M');

class Main {

    public      $hashMap = array();
    
    public function __construct() {

    }
    
    public function getSolution($n) {
        
        $ret = gmp_init(0);
        $modulo = gmp_init($n);
        
        for ($i=1;$i <= 1000;$i++) {
            $temp = gmp_init($i);
            
            for ($j=1;$j < $i;$j++) {
                $temp = gmp_mul($temp, gmp_init($i));
                $temp = gmp_mod($temp, $modulo);
            }
            $ret = gmp_add($ret, $temp);
            $ret = gmp_mod($ret, $modulo);
        }
        return gmp_strval($ret);
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
        
        $this->strProblem = "次の式は, 1**1 + 2**2 + 3**3 + ... + 10**10 = 10405071317 である.\n\n";
        $strPRB = <<< PRB
            
では, 1**1 + 2**2 + 3**3 + ... + 1000**1000 の最後の10桁を求めよ.
PRB;

        $this->strProblem .= $strPRB;
        $arg = "10000000000";

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
