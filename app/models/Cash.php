<?php
class Cash {
    public static function list(int $classId, ?string $month = null, ?int $limit = null) {
        $sql = 'SELECT t.*, u.name AS member_name FROM transactions t LEFT JOIN users u ON u.id = t.member_id WHERE t.class_id = ?';
        $p = [$classId];
        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) { $sql .= " AND DATE_FORMAT(t.trx_date,'%Y-%m') = ?"; $p[] = $month; }
        $sql .= ' ORDER BY t.trx_date DESC, t.id DESC';
        if ($limit) $sql .= ' LIMIT ' . (int)$limit;
        return Database::query($sql, $p)->fetchAll();
    }
    public static function find(int $id, int $classId) {
        return Database::query('SELECT * FROM transactions WHERE id = ? AND class_id = ?', [$id, $classId])->fetch();
    }
    public static function balance(int $classId): float {
        return (float)Database::query(
            "SELECT COALESCE(SUM(IF(type='pemasukan', amount, -amount)),0) FROM transactions WHERE class_id = ?", [$classId]
        )->fetchColumn();
    }
    public static function monthTotal(int $classId, string $type): float {
        return (float)Database::query(
            "SELECT COALESCE(SUM(amount),0) FROM transactions WHERE class_id = ? AND type = ? AND DATE_FORMAT(trx_date,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m')",
            [$classId, $type]
        )->fetchColumn();
    }
    public static function dues(int $classId) {
        return Database::query(
            "SELECT u.id, u.name, COALESCE(SUM(t.amount),0) AS total
             FROM users u LEFT JOIN transactions t ON t.member_id = u.id AND t.type='pemasukan' AND t.category='iuran'
             WHERE u.class_id = ? AND u.role = 'user' GROUP BY u.id, u.name ORDER BY u.name", [$classId]
        )->fetchAll();
    }
    public static function create(int $classId, int $by, array $d) {
        Database::query(
            'INSERT INTO transactions (class_id, type, category, member_id, amount, description, trx_date, created_by) VALUES (?,?,?,?,?,?,?,?)',
            [$classId, $d['type'], $d['category'], $d['member_id'], $d['amount'], $d['description'], $d['trx_date'], $by]
        );
    }
    public static function update(int $id, int $classId, array $d) {
        Database::query(
            'UPDATE transactions SET type=?, category=?, member_id=?, amount=?, description=?, trx_date=? WHERE id=? AND class_id=?',
            [$d['type'], $d['category'], $d['member_id'], $d['amount'], $d['description'], $d['trx_date'], $id, $classId]
        );
    }
    public static function delete(int $id, int $classId) {
        Database::query('DELETE FROM transactions WHERE id = ? AND class_id = ?', [$id, $classId]);
    }
}
