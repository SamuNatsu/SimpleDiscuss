<?php
namespace SimpleDiscuss;

class User {
    static private $_info = null;
    static private $_list = [];
    static private $_iterator = -1;

    // Initialize
    static public function init(): void {
        session_start();

        if (!self::_checkLogin())
            self::_clearLogin();
        else {
            $uid = $_SESSION['uid'];
            self::$_info = Database::readUser("uid=$uid")[0];
            if (self::$_info === null)
                self::_clearLogin();
        }
    }
    static public function fetchAll(): void {
        self::$_list = Database::readUser('1=1');
    }

    static public function begin(): void {
        self::$_iterator = -1;
    }
    static public function next(): bool {
        ++self::$_iterator;
        return isset(self::$_list[self::$_iterator]);
    }
    static public function itrget(string $name) {
        return self::$_list[self::$_iterator][$name] ?? null;
    }

    // Login control
    static public function isLogin(): bool {
        return $_SESSION['login'];
    }
    static public function setLogin(int $uid): void {
        if ($uid <= 0) {
            self::_clearLogin();
            return;
        }

        $_SESSION['login'] = true;
        $_SESSION['time'] = time();
        $_SESSION['uid'] = $uid;
    }

    // Get user info
    static public function get(string $key) {
        return self::$_info[$key] ?? null;
    }
    static public function _get() {
        return self::$_info;
    }

    // Login sub control
    static private function _checkLogin(): bool {
        if (!isset($_SESSION['login']) || !is_bool($_SESSION['login']) || !$_SESSION['login'])
            return false;

        if (!isset($_SESSION['time']) || !is_int($_SESSION['time']) || time() - $_SESSION['time'] > 86400)
            return false;

        if (!isset($_SESSION['uid']) || !is_int($_SESSION['uid']))
            return false;

        return true;
    }
    static private function _clearLogin(): void {
        $_SESSION['login'] = false;
        $_SESSION['time'] = -1;
        $_SESSION['uid'] = 0;
    }

}
