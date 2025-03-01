<?php

class Database {
    private static $instance = null;
    private $db;

    private function __construct() {
        $this->db = new SQLite3(__DIR__ . '/../db/progression_game.sqlite');
        $this->db->exec("PRAGMA foreign_keys = ON;");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->db;
    }
}