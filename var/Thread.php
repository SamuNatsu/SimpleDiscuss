<?php
namespace SimpleDiscuss;

class ThreadExceptoin extends \Exception {}

class Thread {
    static private $_list = [];
    static private $_iterator = -1;

    // Fetch sections from DB
    static public function fetchAll($sid): void {
        self::$_list = Database::readThread("sid=$sid AND head IS NULL", true);
        Database::cleanThread();
    }
    static public function fetch($tid): void {
        self::$_list = Database::readThread("tid=$tid OR head=$tid");
    }

    // Section iterator methods
    static public function begin(): void {
        self::$_iterator = -1;
    }
    static public function next(): bool {
        ++self::$_iterator;
        return isset(self::$_list[self::$_iterator]);
    }
    static public function get(string $name) {
        return self::$_list[self::$_iterator][$name] ?? null;
    }

}
