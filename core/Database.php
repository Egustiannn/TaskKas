<?php
class Database {
    private static $pdo;
    private static $cfg;
    public static function configure(array $cfg) { self::$cfg = $cfg; }
    public static function conn(): PDO {
        if (!self::$pdo) {
            $c = self::$cfg;
            $dsn = "mysql:host={$c['host']};dbname={$c['name']};charset={$c['charset']}";
            self::$pdo = new PDO($dsn, $c['user'], $c['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$pdo;
    }
    public static function query(string $sql, array $params = []): PDOStatement {
        $st = self::conn()->prepare($sql);
        $st->execute($params);
        return $st;
    }
}
