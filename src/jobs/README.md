# Padron reducido job.

Se asume que el padron reducido de SUNAT ha sido descargado y descomprimido, lo cual como resultado el archivo de texto: `padron_reducido_ruc.txt`. 

## Crear base de datos de padrón

Se necesita pasar como parametro la ruta del padron, en el siguiente script.

```bash
php padron_write.php ./padron_reducido_ruc.txt
```

Esto dará como resultado, un archivo `padron.db` en el directorio actual, este es un tipo de base de datos `key-value`. 
> El archivo `padron.db` a la fecha actual pesa alrededor de 1.6GB, pudiendo reducir a 400mb comprimido.

## Consultar padrón

Teniendo la data almacenada en `padron.db` en el directorio actual, se puede realizar la consulta del RUC. 
```bash
php padron_read.php 20416715301
```

> La consulta en el momento de escribir esto, toma alrededor de `0.5 ms` comparando con realizar la consulta directamente en el archivo txt, que toma `2.6 seg`; representa una reducción del `99.98 %`.