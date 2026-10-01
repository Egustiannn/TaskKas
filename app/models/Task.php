<?php
class Task {
    public static function all(int $userId, string $filter = 'semua') {
        $sql = 'SELECT * FROM tasks WHERE user_id = ?';
        if ($filter === 'belum' || $filter === 'selesai') $sql .= " AND status = '$filter'";
        $sql .= " ORDER BY status ASC, deadline ASC, FIELD(priority,'tinggi','sedang','rendah')";
        return Database::query($sql, [$userId])->fetchAll();
    }
    public static function create(int $userId, array $d) {
        Database::query(
            'INSERT INTO tasks (user_id, subject, title, description, deadline, priority, status) VALUES (?,?,?,?,?,?,?)',
            [$userId, $d['subject'], $d['title'], $d['description'], $d['deadline'], $d['priority'], $d['status']]
        );
    }
    public static function update(int $id, int $userId, array $d) {
        Database::query(
            'UPDATE tasks SET subject=?, title=?, description=?, deadline=?, priority=?, status=? WHERE id=? AND user_id=?',
            [$d['subject'], $d['title'], $d['description'], $d['deadline'], $d['priority'], $d['status'], $id, $userId]
        );
    }
    public static function delete(int $id, int $userId) {
        Database::query('DELETE FROM tasks WHERE id = ? AND user_id = ?', [$id, $userId]);
    }
    public static function toggle(int $id, int $userId) {
        Database::query("UPDATE tasks SET status = IF(status='belum','selesai','belum') WHERE id = ? AND user_id = ?", [$id, $userId]);
    }
    public static function activeCount(int $userId): int {
        return (int)Database::query("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'belum'", [$userId])->fetchColumn();
    }
    public static function nearest(int $userId) {
        return Database::query(
            "SELECT subject, title, deadline FROM tasks WHERE user_id = ? AND status = 'belum' AND deadline >= CURDATE() ORDER BY deadline ASC LIMIT 1",
            [$userId]
        )->fetch();
    }
}
