<?php
class Main {
        
        public static $digits = array("one", "two", "three", "four", "five", "six", "seven", "eight", "nine");
        public static $teens = array("ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen");
        public static $tens = array("twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety");
        public static $hundreds = array("onehandred","twohandred","threehandred","fourhandred","fivehandred","sixhandred","sevenhandred","eighthandred","ninehandred");
        public static $thousand = array("onethousand");
        const STR_AND = "and";
    
        public $hashMap = array();
        
        public function getLengthUnder100($i, &$ary) {

            $sum = 0;
            $ary = array();

            if ($i < 10) {
                $sum += strlen(Main::$digits[ $i-1 ]);
                $ary[] = Main::$digits[ $i-1 ];
            }
            elseif (10 <= $i && $i < 20) {
                $sum += strlen(Main::$teens[ $i - 10 ]);
                $ary[] = Main::$teens[ $i - 10 ];
            }
            elseif (20 <= $i && $i < 100) {
                $first = $i % 10;
                $second = $i / 10;
                if ($first === 0) {
                    $sum += strlen(Main::$tens[ $second - 2 ]);
                    $ary[] = Main::$tens[ $second - 2 ];
                }
                else {
                    $sum += strlen(Main::$digits[ $first - 1 ]) + strlen(Main::$tens[ $second - 2 ]);
                    $ary[] = Main::$tens[ $second - 2 ];
                    $ary[] = Main::$digits[ $first - 1 ];
                }
            }
            return $sum;
        }
        
        public function makeHash($limit) {

            $sum = 0;
            $ary = array();
            $arg = array();
            for ($i=1;$i <= $limit;$i++) {
                
                if ($i < 100) {
                    $sum += $this->getLengthUnder100($i, $arg);
                    $ary = array_merge($ary,$arg);
                }
                elseif (100 <= $i && $i < 1000) {
                    $aryi = str_split($i);
                    $sum += strlen(Main::$hundreds[ $aryi[0]-1 ]);
                    $ary = array_merge($ary, array(Main::$hundreds[ $aryi[0] - 1 ] ));
                    $under100 = $i % 100;
                    if ($under100 !== 0) {
                        // plus "and"
                        $sum += strlen(Main::STR_AND);
                        $ary[] = Main::STR_AND;
                        
                        $sum += $this->getLengthUnder100($under100, $arg);
                        $ary = array_merge( $ary, $arg );
                    }
                }
                elseif ($i === 1000) {
                    $sum += strlen(Main::$thousand[ 0 ]);
                    $ary = array_merge($ary,array(Main::$thousand[ 0 ]));
                }
            }
            $this->hashMap[ $limit ] = $sum;
            // ソート
            uasort($this->hashMap, "gmp_cmp");
        }
        
        /**
         * Project euler problem
         */
        public function execute($args=null)
        {
            $this->strProblem = "1 から 5 までの数字を英単語で書けば one, two, three, four, five であり, 全部で 3 + 3 + 5 + 4 + 4 = 19 の文字が使われている.\n\n";
            $strPRB = <<< PRB
では 1 から 1000 (one thousand) までの数字をすべて英単語で書けば, 全部で何文字になるか.

注: 空白文字やハイフンを数えないこと. 例えば, 342 (three hundred and forty-two) は 23 文字, 115 (one hundred and fifteen) は20文字と数える. なお, "and" を使用するのは英国の慣習.
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
            $this->makeHash(1000);

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

