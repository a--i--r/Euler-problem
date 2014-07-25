<?php
    class Main {

        /**
         * Project euler problem 1
         */
        public function execute($args=null)
        {
            $strProblem = "10未満の自然数のうち、3 もしくは 5 の倍数になっているものは 3, 5, 6, 9 の4つがあり、 これらの合計は 23 になる。";
            $strProblem .= "同じようにして、1,000 未満の 3 か 5 の倍数になっている数字の合計を求めよ。";
            $result = 0;
            for ($i=1;$i<1000;$i++) {
                if ($i % 3 === 0) {
                    $result += $i;
                }
                elseif ($i % 5 === 0) {
                    $result += $i;
                }
            }
            return $result;
        }
        
    }
    $main = new Main();
    echo "result: " . $main->execute();
?>