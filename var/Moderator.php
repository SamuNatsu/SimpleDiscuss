<?php
namespace SimpleDiscuss;

class ModeratorExceptoin extends \Exception {}

class Moderator {
    static private $_init = false;
    static private $_list = [];
    static private $_iterator = -1;

    // Fetch sections from DB
    static public function fetchAll(): void {
        if (self::$_init)
            return;
        self::$_list = Database::readUSM('1=1');
        self::$_init = true;
    }

    // Check moderator
    static public function check(int $sid, int $uid): bool {
        foreach (self::$_list as $v)
            if ($v['sid'] == $sid && $v['uid'] == $uid)
                return true;
        return false;
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
