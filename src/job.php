<?php
set_time_limit(0);
//ini_set('memory_limit', '2048M');

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

function parseTxt($txtPath)
{
    $handle = fopen($txtPath, "r") or die("No se puede abrir el txt");
    if ($handle) {
        $isFirst = true;
        while (!feof($handle)) {
            $line = fgets($handle, 4096);
            // Process buffer here..
            $items = str_split($line, '|');
            if ($isFirst) {
                $isFirst = false;
                continue;
            }

            $ruc = $items[0];
        }
        fclose($handle);
    }
}

$pathZip = 'padron.zip';
//downloadFile('http://www.sunat.gob.pe/descargaPRR/padron_reducido_local_anexo.zip', 'anexdo');
downloadFile('http://www2.sunat.gob.pe/padron_reducido_ruc.zip', $pathZip);
$txtName = extractFiles($pathZip, __DIR__);





