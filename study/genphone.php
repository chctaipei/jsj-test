<?php
require_once("s2t.php");

global $count2;
global $loc;
function cmp($a, $b)
{
    // chc: 因為 $count 會被更改, 因此要 copy 一份
    global $count2;
    global $loc;

    if ($count2[$b] == $count2[$a]) {
        // 小的放前面
        return $loc[$a] - $loc[$b];
    } else {
        // 大的放前面
        return $count2[$b] - $count2[$a];
    }
}

    // 同音詞庫 (至少兩個字以上才做同音)
    // https://raw.githubusercontent.com/timothyliu/idioms/master/phone.cin.utf8
    // TODO 問題: 如果一個字有兩個以上的發音, 只會儲存其中一組
    $start = false;
    $fp = fopen("phone.cin.utf8", "r");
    $i = 0;
    while ($buf = trim(fgets($fp))) {
        if ($buf == "%chardef  begin") {
          $start = true;
          continue;
        } else if ($buf == "%chardef  end") {
          break;
        } else if ($start) {
            list($a, $b) = explode("\t", $buf);
            $phone[$b][] = "$a";
            $cin[$a][] = $b;
            if (!isset($count[$b])) {
                $count[$b] = 1;
            } else {
                $count[$b]++;
            }
            $loc[$b] = $i++;
        }
    }

    //arsort($count, SORT_NUMERIC);
    $count2 = $count;
    @uksort($count, "cmp");
    // print_r($count);exit;
/*
print_r($phone["屋"]);
print_r($cin['j']);
print_r($count);
exit;
*/

    $arr = [];
    foreach ($count as $key => $value) {
        foreach ($phone[$key] as $spell) {
            //if (isset($cin[$spell])) {
                /*
                if (!isset($cin[$key])) {
                    $cin[$key] = $cin[$spell];
                } else {
                    $cin[$key] = array_merge($cin[$key], $cin[$spell]);
                }
                */
                if (isset($cin[$spell])) {

                    echo " . implode(", ", $cin[$spell]) . " => $key\n";
/*
                    foreach ($cin[$spell] as $word) {
                        // $arr[$word] = $key;
                        if ($key != $word) {
                            echo "$word => $key\n";
                        }
                    }
*/
                }

                unset($cin[$spell]);
            // }
        }
    }


/*
    $output = "<?php\n\$phone = " .
        str_replace(['{',':','}'], ['[',' =>',']'],
        json_encode($arr, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) .  ";\n" .
        "?>";
    file_put_contents("phone.php", $output);
*/
?>
