<?php
class DashboardController extends Controller {
    public function index() {
        $this->requireLogin();
        $u = $this->user();
        $cid = $u['class_id'];

        if ($u['role'] === 'admin') {
            $month = $_GET['month'] ?? '';
            return $this->view('dashboard/admin', [
                'title' => 'Dashboard Admin',
                'balance' => Cash::balance($cid),
                'income' => Cash::monthTotal($cid, 'pemasukan'),
                'expense' => Cash::monthTotal($cid, 'pengeluaran'),
                'members' => User::members($cid),
                'rows' => Cash::list($cid, $month),
                'dues' => Cash::dues($cid),
                'month' => $month,
            ], 'app');
        }

        $filter = $_GET['status'] ?? 'semua';
        $this->view('dashboard/user', [
            'title' => 'Dashboard',
            'tasks' => Task::all($u['id'], $filter),
            'filter' => $filter,
            'activeTasks' => Task::activeCount($u['id']),
            'nearest' => Task::nearest($u['id']),
            'balance' => Cash::balance($cid),
            'expense' => Cash::monthTotal($cid, 'pengeluaran'),
            'recent' => Cash::list($cid, null, 6),
        ], 'app');
    }
}
