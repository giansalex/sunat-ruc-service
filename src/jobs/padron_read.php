<?php

if (!isset($argv[1])) {
    die('Requiere primer parametro: ruc');
}

$ruc = $argv[1];

if (strlen($ruc) !== 11) {
    die('RUC debe tener 11 digitos');
}

$t = -microtime(1);
$id = dba_open("padron.db", "r", 'cdb');

if (!$id) {
    echo "dba_open failed\n";
    exit;
}

$line = dba_fetch($ruc, $id);
if ($line === false) {
    echo 'Not found'.PHP_EOL;
} else {
    echo utf8_encode($line);
}

dba_close($id);
echo 'Time: '.($t+microtime(1)).PHP_EOL;

