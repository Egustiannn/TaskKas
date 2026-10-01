<?php
class App {
    public function run() {
        $parts = array_values(array_filter(explode('/', trim($_GET['url'] ?? '', '/'))));
        $controller = ucfirst($parts[0] ?? 'home') . 'Controller';
        $method = $parts[1] ?? 'index';
        $params = array_slice($parts, 2);

        if (!preg_match('/^[A-Za-z]+Controller$/', $controller) || !class_exists($controller)) return $this->notFound();
        $obj = new $controller();
        if (!method_exists($obj, $method) || !is_callable([$obj, $method]) || $method[0] === '_') return $this->notFound();
        call_user_func_array([$obj, $method], $params);
    }
    private function notFound() { http_response_code(404); exit('Halaman tidak ditemukan.'); }
}
