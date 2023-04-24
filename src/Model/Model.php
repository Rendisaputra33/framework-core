<?php

namespace Blanks\Framework\Model;

use Blanks\Framework\Database\Manager;
use Exception;
use PDO;

/**
 * @method bool insert(array $data)
 * @method mixed find(string|int $id)
 * @method bool update(array $data, array $filter)
 * @method array get(array $select, array $filter)
 */
abstract class Model
{
    protected string $table, $primaryKey;

    private function find(mixed $id)
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

    private function insert($data)
    {
        $connection = Manager::create();
        $table = $this->table;
        $keys = join(",", array_keys($data));
        $valuesBinding = join(",", array_map(fn ($it) => ":$it", array_values($data)));
        $statement = $connection->prepare("INSERT INTO $table ($keys) VALUES ($valuesBinding)");
        $result = $statement->execute($data);
        Manager::close();
        return $result;
    }

    private function update(array $data, array $filter)
    {
        $connection = Manager::create();
        $table = $this->table;
        $keys = join(",", array_map(fn ($it) => "$it = :$it", array_keys($data)));
        $conditions = array_keys($filter)[0];
        $statement = $connection->prepare("UPDATE $table SET $keys WHERE $conditions = :$conditions");
        $result = $statement->execute([...$data, ...$filter]);
        Manager::close();
        return $result;
    }

    private function get(array $select, array $filter)
    {
        $connection = Manager::create();
        $table = $this->table;
        $keys = join(",", $select);
        $buildFilter = join(" AND ", array_map(fn ($it) => "$it = :$it", array_keys($filter)));
        $statement = $connection->prepare("SELECT $keys FROM $table WHERE $buildFilter");

        foreach ($filter as $key => $value)
            $statement->bindParam(":$key", $value);

        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        Manager::close();
        return $result;
    }

    public static function __callStatic($name, $arguments)
    {
        $object = new static();
        if (method_exists($object, $name)) {
            $result = call_user_func_array([$object, $name], $arguments);
            unset($object);
            return $result;
        }
        unset($object);
        throw new Exception("Method {$name} tidak tersedia!");
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        throw new Exception("Method {$name} tidak tersedia!");
    }
}
