<?php

namespace Blanks\Framework\Model;

use Blanks\Framework\Database\Manager;
use Exception;
use PDO;

/**
 * @static bool insert(array $data)
 * @static mixed find(string|int $id)
 * @static bool update(array $data, array $filter)
 * @static array get(array $select, array $filter)
 * @static array|object query(string $query, array $binding = [], bool $isSingle = false)
 * @static bool executeQuery(string $query, array $binding = [])
 * @static Model with(array $relations)
 * @method bool insert(array $data)
 * @method mixed find(string|int $id)
 * @method bool update(array $data, array $filter)
 * @method array get(array $select, array $filter)
 * @method array|object query(string $query, array $binding = [], bool $isSingle = false)
 * @method bool executeQuery(string $query, array $binding = [])
 * @method Model with(array $relations)
 */
abstract class Model
{
    protected string $table, $primaryKey;
    protected array $relations = [];

    private function find(mixed $id)
    {
        $connection = Manager::create();
        $table = $this->table;
        $primaryKey = $this->primaryKey;

        $statement = $connection->prepare("SELECT * FROM $table WHERE $primaryKey = :id");
        $statement->bindParam(':id', $id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_OBJ);
        Manager::close();
        return $result;
    }

    private function insert($data)
    {
        $connection = Manager::create();
        $table = $this->table;
        $keys = join(",", array_keys($data));
        $valuesBinding = join(",", array_map(fn ($it) => ":$it", array_keys($data)));
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
        $buildFilter = empty($buildFilter) ? "" : "WHERE " . $buildFilter;
        $statement = $connection->prepare("SELECT $keys FROM $table $buildFilter");

        foreach ($filter as $key => $value)
            $statement->bindParam(":$key", $value);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        Manager::close();
        return $result;
    }

    private function query(string $query, array $binding = [], bool $isSingle = false)
    {
        $connection = Manager::create();
        $statement = $connection->prepare($query);

        foreach ($binding as $key => $value) {
            $statement->bindParam(":$key", $value);
        }

        $statement->execute();
        $result = $isSingle 
            ? $statement->fetch(PDO::FETCH_OBJ)
            : $statement->fetchAll(PDO::FETCH_OBJ);

        Manager::close();
        return $result;
    }

    private function findByKey(string $key, string $value): mixed {
        $connection = Manager::create();
        $table = $this->table;

        $statement = $connection->prepare("SELECT * FROM $table WHERE $key = :key");
        $statement->bindParam(':key', $value);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_OBJ);
        Manager::close();
        return $result;
    }

    private function with(array $relations): self {
        $this->relations = $relations;
        return $this;
    }

    public function single(int|string $id): mixed {
        $dataParent = $this->find($id);

        foreach ($this->relations as [
            'name' => $name,
            'model' => $model,
            'key' => ['local' => $local, 'foreign' => $foreign],
            'cardinality' => $cardinality
        ]) {
            if ($cardinality == 'HasMany') {
                $dataParent->{$name} = $model::get(['*'], [$foreign => $dataParent->{$local}]);
            } else {
                $dataParent->{$name} = $model::findByKey($foreign, $dataParent->$local);
            }
        }

        return $dataParent;
    }

    public function all(array $select = ['*'], array $filter = []): mixed {
        $dataParent = $this->get($select, $filter);

        foreach ($dataParent as &$data) {
            foreach ($this->relations as [
                'name' => $name,
                'model' => $model,
                'key' => ['local' => $local, 'foreign' => $foreign],
                'cardinality' => $cardinality
            ]) {
                if ($cardinality == 'HasMany') {
                    $data->{$name} = $model::get(['*'], [$foreign => $data->{$local}]);
                } else {
                    $data->{$name} = $model::findByKey($foreign, $data->$local);
                }
            }
        }

        return $dataParent;
    }

    private function executeQuery(string $query, array $binding = [])
    {
        $connection = Manager::create();
        $statement = $connection->prepare($query);

        foreach ($binding as $key => $value) {
            $statement->bindParam(":$key", $value);
        }
        $result = $statement->execute();
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
