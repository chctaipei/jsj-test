<?php
function xfile($filename) {
    $file = fopen($filename, "r");
    while (($line = fgets($file)) !== false) {
        echo "hihi";
        yield $line;
    }
    fclose($file);
}

foreach (xfile("/tmp/new.json") as $line) {
    echo "...\n";
    // echo memory_get_usage(true) . "\n";
    // $data = json_decode($line, 1);
}
