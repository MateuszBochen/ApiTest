<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 13:10
 */

namespace Library\DataBase;


interface Driver
{

    public function getPdo();

    public function update(string $tableName, array $valuesKeyValue, array $conditionsKeyValue);

    public function insert(string $tableName, array $valuesKeyValue);

    public function lastId(): int;

    public function query(string $query) : \PDOStatement;

    public function get();

    public function getQueriesLog(): array;

    public function addToQueryLog(string $query);

    public function exec();
}