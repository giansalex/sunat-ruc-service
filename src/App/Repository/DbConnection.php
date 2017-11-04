<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 04/11/2017
 * Time: 10:15 AM
 */

namespace Sunat\Ruc\Repository;

/**
 * Class DbConnection
 * @package Sunat\Ruc\Repository
 */
class DbConnection
{
    /**
     * @var \PDO
     */
    private $con;
    /**
     * @var string
     */
    private $dsn;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;
    /**
     * Connection constructor.
     * @param array $params
     */
    public function __construct($params)
    {
        $this->dsn = $params['dsn'];
        $this->user = $params['user'];
        $this->password = $params['password'];
    }
    /**
     * Return connection.
     *
     * @return \PDO
     */
    public function getConnection()
    {
        if (!$this->con) {
            $this->con = new \PDO($this->dsn, $this->user, $this->password);
        }
        return $this->con;
    }

    /**
     * Fetch all rows.
     *
     * @param string $query
     * @param array|null $params
     * @param int|null $fetch_style
     * @return array
     */
    public function fetchAll($query, $params = null, $fetch_style = \PDO::FETCH_ASSOC)
    {
        $con = $this->getConnection();
        $stm = $con->prepare($query);
        $stm->execute($params);
        $all = $stm->fetchAll($fetch_style);
        $stm = null;
        return $all;
    }

    /**
     * @param $query
     * @param array|null $params
     * @return bool
     */
    public function exec($query, $params = null)
    {
        $con = $this->getConnection();
        $stm = $con->prepare($query);
        $state = $stm->execute($params);
        $stm = null;
        return $state;
    }
}