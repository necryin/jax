<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax;

use Necryin\Jax\Exception\ConnectionException;

/**
 * Class DbConnection
 * @package Necryin\Jax\Core\Application
 */
class DbConnection
{

    /**
     * @var \PDO
     */
    private $connection;
    
    /**
     * DbConnection constructor.
     * @param string $dsn
     * @param string $user
     * @param string $password
     * @param array $options
     * @throws ConnectionException
     */
    public function __construct($dsn, $user, $password, array $options = [])
    {
        try {
            $this->connection = new \PDO($dsn, $user, $password, $options);
        } catch (\Exception $e) {
            throw new ConnectionException('Connection to Db failed');
        }
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
