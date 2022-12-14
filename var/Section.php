<?php
namespace SimpleDiscuss;

class SectionExceptoin extends \Exception {}

class Section {
    static private $_list = [];
    static private $_iterator = -1;

    // Fetch sections from DB
    static public function fetchAll(): void {
        self::$_list = Database::readSection('1=1');
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
