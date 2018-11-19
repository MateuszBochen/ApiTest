<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 14:47
 */

namespace Repository;

use Library\DataBase\Repository;

class PersonRepository extends Repository
{
    public function findAllPersonsWithWebsite()
    {
        $query = "SELECT * FROM {$this->tableName}";
        $query .= " JOIN `person_data` ON {$this->tableName}.id = `person_data`.`person_id` AND (`person_data`.`website` IS NOT NULL AND `person_data`.`website` != '')";
        $query = $this->driver->query($query);
        $query->execute();

        return $this->getResults($query);
    }
}
