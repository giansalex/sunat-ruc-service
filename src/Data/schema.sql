CREATE TABLE company
(
  ruc CHAR(11) PRIMARY KEY,
  nombre_razon_social VARCHAR(250),
  estado_del_contribuyente VARCHAR(20),
  condicion_de_domicilio VARCHAR(20),
  ubigeo VARCHAR(8),
  tipo_de_via VARCHAR(20),
  nombre_de_via VARCHAR(100),
  codigo_de_zona VARCHAR(20),
  tipo_de_zona VARCHAR(50),
  numero VARCHAR(15),
  interior VARCHAR(15),
  lote VARCHAR(15),
  dpto VARCHAR(15),
  manzana VARCHAR(15),
  kilometro VARCHAR(15)
)ENGINE = INNODB;