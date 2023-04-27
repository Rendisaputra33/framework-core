<?php

namespace Blanks\Framework\Database;

use PDO;

class Manager
{
    private static ?PDO $connection = null;

    /**
     * creator connection
     * @return PDO|null
     */
    public static function create(): ?PDO
    {
        $host = env('DB_HOST');
        $database = env('DB_SELECTED');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $connection = new PDO(
            "mysql:host=$host;dbname=$database",
            $username,
            $password
        );
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        self::$connection = $connection;
        return self::$connection;
    }

    /**
     * closing connection
     * @return void
     */
    public static function close(): void
    {
        self::$connection = null;
    }
}
