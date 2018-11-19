<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 13:11
 */

namespace Library\DataBase\MySql;


use Library\Config\ConfigParam;

class Driver implements \Library\DataBase\Driver
{
    private $pdo;
    private $insertColumns;
    private $addingToColumnsList = true;
    private $queriesLog = [];
    private $query;

    public function __construct(ConfigParam $database)
    {
        $database = $database->getParam();

        $host = $database['host'];
        $db = $database['database'];
        $password = $database['password'];
        $user = $database['user'];

        $this->pdo = new \PDO('mysql:host='.$host.';dbname='.$db, $user, $password);
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function delete(string $tableName, array $conditions)
    {
        $conditions = $this->prepareConditions($conditions);

        $query = "DELETE FROM `{$tableName}` WHERE $conditions LIMIT 1";
        $this->queriesLog[] = $query;

        $this->query($query)->execute();
    }

    public function update(string $tableName, array $array, array $conditions)
    {
        $conditions = $this->prepareConditions($conditions);
        $values = $this->prepareValuesToUpdate($array);

        $query = "UPDATE `{$tableName}` SET {$values} WHERE $conditions LIMIT 1";
        $this->queriesLog[] = $query;

        $this->query($query)->execute();
    }

    public function insert(string $tableName, array $array)
    {
        $values = [];

        $this->insertColumns = null;
        $this->addingToColumnsList = true;
        if (isset($array[0]) && is_array($array[0])) {
            foreach ($array as $item) {
                $values[] = $this->prepareValuesToInsert($item);
                $this->addingToColumnsList = false;
            }
            $values = implode(', ', $values);
        }
        else {
            $values = $this->prepareValuesToInsert($array);
        }

        $columns = $this->prepareColumnsToInsert();
        $query = "INSERT INTO `{$tableName}` {$columns} VALUES {$values}";
        $this->query($query)->execute();


        return $this->lastId();
    }

    public function lastId(): int
    {
        return $this->pdo->lastInsertId();
    }

    public function query(string $query) : \PDOStatement
    {
        $this->queriesLog[] = $query;
        return $this->pdo->prepare($query);

    }

    public function get()
    {
        $this->query->execute();
        return $this->query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getQueriesLog(): array
    {
        return $this->queriesLog;
    }

    public function addToQueryLog(string $query)
    {
        $this->queriesLog[] = $query;
        return $this;
    }

    /**
     * @return mixed
     * @throws MysqlException
     */
    public function exec()
    {
        try {
            return $this->query->execute();
        }
        catch(\PDOException $e) {
            throw new MysqlException($e->getMessage(), $this->getQueriesLog());
        }
    }

    private function prepareValuesToInsert(array $array)
    {
        array_walk($array, function(&$val, $column){
            $val = addslashes($val);
            $val = "'{$val}'";
            if ($this->addingToColumnsList) {
                $this->insertColumns[] = "`{$column}`";
            }
        });
        return '('.implode(', ', $array).')';
    }

    private function prepareColumnsToInsert()
    {
        return '('.implode(', ', $this->insertColumns).')';
    }

    private function prepareValuesToUpdate(array $array)
    {
        return implode(', ', $this->makeArrayColumnToValue($array));
    }

    public function prepareConditions(array $array)
    {
        return implode(' AND ', $this->makeArrayColumnToValue($array));
    }

    private function makeArrayColumnToValue($array)
    {
        $tmp = [];
        foreach ($array as $column => $val) {
            $val = addslashes($val);
            $tmp[] = "`$column` = '{$val}'";
        }
        return $tmp;
    }
}
