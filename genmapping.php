<?php
    // 簡繁同義
    // https://github.com/pkg-ime/opencc/blob/4708ba568be464208fcb53767b641efa6aa6dfe9/data/tw/to_tw_phrases.txt
    $fp = fopen("to_tw_phrases.txt", "r");
    while ($buf = fgets($fp)) {
        list($a, $b) = explode("\t", trim($buf));
        $phase[$a] = $b;
    }

    // 簡繁片語
    // http://tongwen.openfoundry.org/src/tongwen_table/phrase_s2t.txt
    $fp = fopen("phrase_s2t.txt", "r");
    while ($buf = trim(fgets($fp))) {
        list($a, $b) = explode(",", $buf);
        $phase[$a] = $b;
    }

    // 簡繁單字
    // http://tongwen.openfoundry.org/src/tongwen_table/word_s2t.txt
    $fp = fopen("word_s2t.txt", "r");
    while ($buf = trim(fgets($fp))) {
        list($a, $b) = explode(",", $buf);
        //$word[$a] = $b;
        $phase[$a] = $b;
    }

    // 同音詞庫 (至少兩個字以上才做同音)
    // https://raw.githubusercontent.com/timothyliu/idioms/master/phone.cin.utf8
    // TODO 問題: 如果一個字有兩個以上的發音, 只會儲存其中一組
    $start = false;
    $fp = fopen("phone.cin.utf8", "r");
    while ($buf = trim(fgets($fp))) {
        if ($buf == "%chardef  begin") {
          $start = true;
          continue;
        } else if ($buf == "%chardef  end") {
          break;
        } else if ($start) {
            list($a, $b) = explode("\t", $buf);
            $phone[$b] = "$a";
        }
    }

    $output = "<?php\n\$phase = " . 
        str_replace(['{',':','}'], ['[',' =>',']'], 
        json_encode($phase, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) .  ";\n" .
        "?>";
    file_put_contents("s2t.php", $output);

    $output = "<?php\n\$phone = " . 
        str_replace(['{',':','}'], ['[',' =>',']'], 
        json_encode($phone, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) .  ";\n" .
        "?>";
    file_put_contents("phone.php", $output);
?>
