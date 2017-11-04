<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 04/11/2017
 * Time: 10:16 AM
 */

namespace Sunat\Ruc\Repository;

use Sunat\Ruc\Models\Company;

/**
 * Class RucRepository
 * @package Sunat\Ruc\Repository
 */
class RucRepository
{
    /**
     * @var DbConnection
     */
    private $db;
    /**
     * CompanyRepository constructor.
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        $this->db = $dbConnection;
    }

    /**
     * Return true if ruc exist.
     *
     * @param string $ruc
     * @return bool
     */
    public function exist($ruc)
    {
        $sql = <<<SQL
SELECT COUNT(ruc) FROM Company WHERE ruc = '$ruc'
SQL;
        $con = $this->db->getConnection();
        $res = $con->query($sql);
        $count = $res->fetchColumn();
        $res = null;

        return $count > 0;
    }
    /**
     * Add a new company.
     *
     * @param Company $company
     * @return bool
     */
    public function addOrUpdate(Company $company)
    {
        $arguments = [
            $company->nombre_razon_social,
            $company->estado_del_contribuyente,
            $company->condicion_de_domicilio,
            $company->ubigeo,
            $company->tipo_de_via,
            $company->nombre_de_via,
            $company->codigo_de_zona,
            $company->tipo_de_zona,
            $company->numero,
            $company->interior,
            $company->lote,
            $company->dpto,
            $company->manzana,
            $company->kilometro,
        ];
        if ($this->exist($company->ruc)) {
            $arguments = array_merge($arguments, [$company->ruc]);
            $sql = <<<SQL
UPDATE company SET nombre_razon_social=?,estado_del_contribuyente=?,condicion_de_domicilio=?,ubigeo=?,tipo_de_via=?,nombre_de_via=?,codigo_de_zona=?,tipo_de_zona=?,numero=?,interior=?,lote=?,dpto=?,manzana=?,kilometro=? WHERE ruc=?
SQL;
        } else {
            $arguments = array_merge([$company->ruc], $arguments);
            $sql = <<<SQL
INSERT INTO company(ruc,nombre_razon_social,estado_del_contribuyente,condicion_de_domicilio,ubigeo,tipo_de_via,nombre_de_via,codigo_de_zona,tipo_de_zona,numero,interior,lote,dpto,manzana,kilometro)
VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
SQL;
        }

        return $this->db->exec($sql, $arguments);
    }

    /**
     * @param string $ruc
     * @return Company
     */
    public function get($ruc)
    {
        $sql = <<<SQL
SELECT ruc,nombre_razon_social,estado_del_contribuyente,condicion_de_domicilio,ubigeo,tipo_de_via,nombre_de_via,codigo_de_zona,tipo_de_zona,numero,interior,lote,dpto,manzana,kilometro FROM company
WHERE ruc = ?
SQL;
        $con = $this->db->getConnection();
        $stm = $con->prepare($sql);
        $stm->execute([$ruc]);
        $cp = $stm->fetchObject(Company::class);
        $stm = null;

        return $cp;
    }
}