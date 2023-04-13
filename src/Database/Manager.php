<?php

namespace Blanks\Framework\Database;

use PDO;

class Manager
{
    private const CONFIG = [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'food_delivery'
    ];

    private static ?PDO $connection = null;

    /**
     * creator connection
     * @return PDO|null
     */
    public static function create(): ?PDO
    {
        $host = self::CONFIG['host'];
        $database = self::CONFIG['database'];
        $username = self::CONFIG['username'];
        $password = self::CONFIG['password'];

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
