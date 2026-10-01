<?php
class User {
    public static function findByEmail(string $email) {
        return Database::query('SELECT * FROM users WHERE email = ?', [$email])->fetch();
    }
    public static function create(array $d) {
        Database::query(
            'INSERT INTO users (class_id, name, email, password, role) VALUES (?,?,?,?,?)',
            [$d['class_id'], $d['name'], $d['email'], password_hash($d['password'], PASSWORD_DEFAULT), 'user']
        );
    }
    public static function members(int $classId) {
        return Database::query("SELECT id, name FROM users WHERE class_id = ? AND role = 'user' ORDER BY name", [$classId])->fetchAll();
    }
    public static function classes() {
        return Database::query('SELECT id, name FROM classes ORDER BY name')->fetchAll();
    }
}
