<?php
/**
 * @author Kirilenko Georgii <g.kirilenko@dengionline.com>
 * @date 24.10.16
 */

namespace Necryin\Jax\Model;

/**
 * Class Comment
 * @package Necryin\Jax\Model
 */
class Comment extends Model
{

    /**
     * {@inheritdoc}
     */
    public function getTableName()
    {
        return 'comments';
    }

    /**
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->getTableName()} WHERE id = ?");
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getRoots($limit = 100)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->getTableName()} WHERE root_id IS NULL ORDER BY date_created DESC LIMIT ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param int $rootId
     * @return array
     */
    public function getTree($rootId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->getTableName()} WHERE root_id = ? ORDER BY date_created DESC");
        $stmt->bindValue(1, (int)$rootId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param string $text
     * @param int|null $parentId
     * @return bool
     */
    public function create($text, $parentId = null)
    {
        // Just create root
        if(empty($parentId)) {
            $createRoot = $this->db->prepare("INSERT INTO {$this->getTableName()} SET text = ?, left_key=1, right_key=2, level=1");
            $createRoot->bindValue(1, htmlspecialchars($text), \PDO::PARAM_STR);
            $createRoot->execute();
            $id = $this->db->lastInsertId();
            if($id === false) {
                return false;
            }
            return $this->getById($id);
        }

        try {
            $this->db->beginTransaction();
            $parent = $this->getById($parentId);
                
            if($parent === false) {
                $this->db->rollBack();
                return false;
            }

            $root = $parent['root_id'] == null ? $parent : $this->getById($parent['root_id']);

            if($root === false) {
                $this->db->rollBack();
                return false;
            }

            $updateTree = $this->db->prepare("UPDATE {$this->getTableName()} SET left_key = left_key + 2, right_key = right_key + 2 WHERE left_key > ? AND root_id = ?");
            $updateTree->bindValue(1, (int)$root['right_key'], \PDO::PARAM_INT);
            $updateTree->bindValue(2, (int)$root['id'], \PDO::PARAM_INT);
            $updateTree->execute();

            $updateRoot = $this->db->prepare("UPDATE {$this->getTableName()} SET right_key = right_key + 2 WHERE id = ?");
            $updateRoot->bindValue(1, (int)$root['id'], \PDO::PARAM_INT);
            $updateRoot->execute();

            $insertNode = $this->db->prepare("INSERT INTO {$this->getTableName()} SET text = :text, left_key = :left_key, right_key = :right_key, root_id = :root_id, parent_id = :parent_id, level = :level");
            $insertNode->bindValue('left_key', (int)$root['right_key'], \PDO::PARAM_INT);
            $insertNode->bindValue('right_key', (int)$root['right_key'] + 1, \PDO::PARAM_INT);
            $insertNode->bindValue('root_id', (int)$root['id'], \PDO::PARAM_INT);
            $insertNode->bindValue('parent_id', (int)$parent['id'], \PDO::PARAM_INT);
            $insertNode->bindValue('level', (int)$parent['level'] + 1, \PDO::PARAM_INT);
            $insertNode->bindValue('text', htmlspecialchars($text), \PDO::PARAM_INT);
            $insertNode->execute();

            $id = $this->db->lastInsertId();
            if($id === false) {
                $this->db->rollBack();
                return false;
            }
            $result = $this->getById($id);
            
            $this->db->commit();

            return $result;
        } catch (\Exception $exception) {
            error_log(sprintf("EXCEPTION - File: %s; Line: %s; Message: %s", $exception->getFile(), $exception->getLine(), $exception->getMessage()));
            $this->db->rollBack();
        }

        return false;
    }

    /**
     * @param string $text
     * @param int $id
     * @return bool
     */
    public function update($text, $id)
    {
        $update = $this->db->prepare("UPDATE {$this->getTableName()} SET text = ? WHERE id = ?");
        $update->bindValue(1, htmlspecialchars($text), \PDO::PARAM_STR);
        $update->bindValue(2, (int)$id, \PDO::PARAM_INT);
        $update->execute();
        return (bool)$update->rowCount();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $update = $this->db->prepare("UPDATE {$this->getTableName()} SET deleted = 1 WHERE id = ?");
        $update->bindValue(1, (int)$id, \PDO::PARAM_INT);
        $update->execute();
        return (bool)$update->rowCount();
    }

}
