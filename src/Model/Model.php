<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax\Model;

use Necryin\Jax\DbConnection;

/**
 * Class Model
 * @package Necryin\Jax\Model
 */
abstract class Model
{
    /**
     * @var DbConnection
     */
    protected $db;

    /**
     * CommentModel constructor.
     * @param DbConnection $db
     */
    public function __construct(DbConnection $db)
    {
        $this->db = $db->getConnection();
    }

    /**
     * @return string 
     */
    abstract public function getTableName();
}
