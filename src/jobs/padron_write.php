<?php

set_time_limit(0);
ini_set('memory_limit', '-1');

if (!isset($argv[1])) {
    die('Requiere primer parametro: ruta del padron reducido en .txt');
}

$padron = $argv[1];

if (!file_exists($padron)) {
    die('El archivo '.$padron.' no existe.');
}

function writeRows($txtPath, $dbr)
{
    $handle = fopen($txtPath, "r") or die("No se puede abrir el txt");

    fgets($handle, 1024); // Excluir cabecera.
    while (!feof($handle)) {
        $line = fgets($handle, 1024);

        $ruc = substr($line, 0, 11);
        // Se podria excluir ruc ya que va implicito en el key.
        // $line = substr($line, 12); // 12 (longitud del ruc + palote)
        dba_insert($ruc, $line, $dbr);
    }

    fclose($handle);
}

$t = -microtime(1);
$id = dba_open("padron.db", "n", 'cdb_make');

if (!$id) {
    echo "dba_open failed\n";
    exit;
}

writeRows($padron, $id);

dba_close($id);

echo 'Time: '.($t+microtime(1)).PHP_EOL;
echo 'Peak memory: '. memory_get_peak_usage(true).PHP_EOL;
echo 'Memory: '. memory_get_usage(true).PHP_EOL;