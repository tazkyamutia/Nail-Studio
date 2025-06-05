<?php
class configdb {
    private static $instance;
    public static function getInstance() {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }
    public function getConnection() {
        // Dummy PDO object, ganti dengan koneksi asli Anda
        return null;
    }
    public function insert($query, $params, $types) { return 1; }
    public function fetchOne($query, $params, $types) { return ['photo'=>'']; }
    public function execute($query, $params, $types) { return true; }
    public function fetchAll($query) { return []; }
    public function getStats() { return []; }
}
