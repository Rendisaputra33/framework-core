<?php

namespace Blanks\Framework\Model;

use Blanks\Framework\Database\Manager;

abstract class Model
{
    protected string $table, $primaryKey;

    public function find(mixed $id)
    {
        $connection = Manager::create();
        $table = $this->table;
        $primaryKey = $this->primaryKey;

        $statement = $connection->prepare("SELECT * FROM $table WHERE $primaryKey = :id");
        $statement->bindParam('id', $id);
        $result = $statement->fetchObject();
        Manager::close();
        return $result;
    }
}
