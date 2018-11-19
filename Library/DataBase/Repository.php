<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 14:46
 */

namespace Library\DataBase;


class Repository
{
    protected $driver;
    protected $model;
    protected $modelClass;

    protected $tableName;

    public function __construct(Driver $driver, $model)
    {
        $this->driver = $driver;
        $this->modelClass = get_class($model);
        $this->model = $model;
        $this->tableName = Helper::getTableName($this->modelClass);
    }

    public function findAll()
    {
        $query = "SELECT * FROM {$this->tableName} WHERE 1";
        $query = $this->driver->query($query);
        $query->execute();

        return $this->getResults($query);
    }

    public function findOneBy(array $conditions)
    {
        $where = $this->prepareWhere($conditions);

        $query = "SELECT * FROM {$this->tableName} {$where} LIMIT 1";
        $query = $this->driver->query($query);
        $query->execute();

        $results = $this->getResults($query);
        if($results && isset($results[0])) {
            $this->model = $results[0];
            return $results[0];
        }

        return null;
    }

    public function save() {
        if ($this->model->id) {
            $this->update();
        } else {
            $this->saveData();
        }
    }

    public function delete()
    {
        $this->driver->delete($this->tableName, ['id' => $this->model->id]);
    }

    protected function update()
    {
        $modelData = get_object_vars($this->model);

        if(array_key_exists('id', $modelData)) {
            unset($modelData['id']);
        }

        $this->driver->update($this->tableName, $modelData, ['id' => $this->model->id]);
    }

    protected function saveData()
    {
        $modelData = get_object_vars($this->model);
        if(array_key_exists(('id'), $modelData)) {
            unset($modelData['id']);
        }
        $lastId = $this->driver->insert($this->tableName, $modelData);
        $this->model->id = $lastId;
    }

    protected function getResults(\PDOStatement $query)
    {
        return $query->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->modelClass);
    }

    protected function prepareWhere(array $conditions)
    {
        $whereColumns = [];
        foreach ($conditions as $column => $value) {
            $snakeCaseColumn = Helper::transformToSnakeCase($column);
            $whereColumns[] = "`{$snakeCaseColumn}` = '{$value}'";
        }
        return 'WHERE '.implode(' AND ', $whereColumns);
    }
}
