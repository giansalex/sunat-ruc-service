<?php
require '../vendor/autoload.php';
set_time_limit(0);
//ini_set('memory_limit', '2048M');

$settings = require __DIR__ . '/../src/settings.php';
$repo = new \Sunat\Ruc\Repository\RucRepository(new \Sunat\Ruc\Repository\DbConnection($settings['settings']['db']));

function downloadFile($url, $path)
{
    $fp = fopen ($path, 'w+');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    //curl_setopt($ch, CURLOPT_TIMEOUT, 50);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

function extractFiles($zipPath, $dir)
{
    $zip = new ZipArchive();
    $res = $zip->open($zipPath);
    if ($res === true && $zip->numFiles > 0) {
        $name = $zip->getNameIndex(0);
        $zip->extractTo($dir);
        $zip->close();

        return $name;
    } else {
        die('No se pudo extraer los archivo del zip, code:' . $res);
    }
}

function saveLine($line)
{
    global $repo;

    $items = explode('|', $line);
    $cp = new \Sunat\Ruc\Models\Company();
    $cp->ruc = $items[0];
    $cp->nombre_razon_social = $items[1];
    $cp->estado_del_contribuyente = $items[2];
    $cp->condicion_de_domicilio = $items[3];
    $cp->ubigeo = $items[4];
    $cp->tipo_de_via = $items[5];
    $cp->nombre_de_via = $items[6];
    $cp->codigo_de_zona = $items[7];
    $cp->tipo_de_zona = $items[8];
    $cp->numero = $items[9];
    $cp->interior = $items[10];
    $cp->lote = $items[11];
    $cp->dpto = $items[12];
    $cp->manzana = $items[13];
    $cp->kilometro = $items[14];

    $repo->addOrUpdate($cp);
}

function parseTxt($txtPath)
{
    $handle = fopen($txtPath, "r") or die("No se puede abrir el txt");
    $lines = 0;

    if ($handle) {
        $isFirst = true;
        while (!feof($handle)) {
            $line = utf8_encode(fgets($handle, 4096));
            if ($isFirst) {
                $isFirst = false;
                file_put_contents('file.txt', $line);
                continue;
            }

            if ($lines > 10000) {
                return;
            }
            $lines++;
            saveLine($line);
        }
        fclose($handle);
    }
}

$pathZip = 'padron.zip';
//downloadFile('http://www.sunat.gob.pe/descargaPRR/padron_reducido_local_anexo.zip', 'anexdo');
//downloadFile('http://www2.sunat.gob.pe/padron_reducido_ruc.zip', $pathZip);
//$txtName = extractFiles($pathZip, __DIR__);
$txtName = 'padron_reducido_ruc.txt';

parseTxt($txtName);




