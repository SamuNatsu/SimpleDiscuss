<?php
namespace SimpleDiscuss;

class RouterException extends \Exception {}

class Router {
    static private $_table = [];

    static public $matches = null;
    static public $path = null;
    static public $queries = [];

    // Register router
    static public function register(string $pattern, string $widget): void {
        if (isset(self::$_table[$pattern]))
            throw new RouterException('Router pattern duplicated', 1);

        self::$_table[$pattern] = $widget;
    }

    // Despatch router
    static public function despatch(): void {
        try {
            self::parseUrl();

            foreach (self::$_table as $k => $v)
                if (preg_match($k, self::$path, self::$matches) === 1) {
                    Widget::get($v)->render();
                    return;
                }

            Widget::get('404')->render();
        }
        catch (\Throwable $e) {
            $prv = $e->getPrevious();
            if ($prv !== null && $prv->getCode() === 4) {
                Widget::get('404')->render();
                return;
            }
            Widget::get('500')->err = $e;
            Widget::get('500')->render();
        }
    }

    // Parse router info
    static private function parseUrl(): void {
        if (count($_GET) > 1)
            throw new RouterException('Invalid router request', 2);

        self::$path = count($_GET) === 0 ? '' : array_keys($_GET)[0];

        $offset = strpos(self::$path, '?');
        if ($offset !== false) {
            $str = substr($_GET['r'], $offset);
            self::$path = substr($_GET['r'], 0, $offset);
            $ls = explode('&', $str);

            foreach ($ls as $seg) {
                $blk = explode('=', $ls);
                if (count($blk) != 2)
                    continue;
                self::$queries[$blk[0]] = $blk[1];
            }
        }
    }

}
