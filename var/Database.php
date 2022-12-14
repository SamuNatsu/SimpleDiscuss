<?php
namespace SimpleDiscuss;

class DatabaseException extends \Exception {}

class Database {
    static private $_db = null;

    // Connect to database and initialize
    static public function init(): void {
        self::$_db = new \mysqli();

        if (!@self::$_db->real_connect(__SIMPLE_DISCUSS_DB_HOST__, __SIMPLE_DISCUSS_DB_USER__, __SIMPLE_DISCUSS_DB_PASS__, __SIMPLE_DISCUSS_DB_NAME__))
            throw new DatabaseException('Fail to connect to database', 1);

        if (!@self::$_db->set_charset('utf8mb4'))
            throw new DatabaseException('Fail to set charset');
    }

    // Clear database and rebuild it
    static public function reset(): void {
        self::_dropTable('users');
        self::_dropTable('sections');
        self::_dropTable('threads');
        self::_dropTable('u_s_manager');
        self::_dropTable('options');

        self::_createTable('users', [
            'uid'       => 'INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name'      => 'CHAR(20) NOT NULL',
            'email'     => 'CHAR(64) NOT NULL UNIQUE',
            'password'  => 'CHAR(64) NOT NULL',
            'privilege' => 'INT NOT NULL DEFAULT 0'
        ]);
        self::_createTable('sections', [
            'sid'  => 'INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' => 'CHAR(20) NOT NULL UNIQUE',
            'desc' => 'TEXT'
        ]);
        self::_createTable('threads', [
            'tid'       => 'INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'sid'       => 'INT NOT NULL',
            'uid'       => 'INT NOT NULL',
            'timestamp' => 'LONG NOT NULL',
            'content'   => 'MEDIUMTEXT NOT NULL',
            'head'      => 'INT'
        ]);
        self::_createTable('u_s_manager', [
            'sid'      => 'INT NOT NULL',
            'uid'      => 'INT NOT NULL',
            'privilege' => 'INT NOT NULL'
        ], ["PRIMARY KEY (sid, uid)"]);
        self::_createTable('options', [
            'name' => 'CHAR(20) NOT NULL PRIMARY KEY',
            'data' => 'MEDIUMTEXT NOT NULL'
        ]);

        self::createUser('Owner', 'owner@owner.owner', md5(sha1('asdfQWER1234')));
        self::updateUser(1, ['privilege' => '2']);

        self::createSection('Test Section');
        self::updateSection(1, ['desc' => '"This is a test section"']);

        self::createThread(1, 1, serialize([
            'title' => 'Test thread', 
            'content' => 'This is a test message'
        ]));

        self::createOption('site_name', 'Simple Discuss');
        self::createOption('header_block', serialize([
            'enable' => true, 
            'content' => '<div style="font-size:large;text-align:center;margin:30px auto">This is a test header</div>'
        ]));
    }

    // User CRUD
    static public function createUser(string $name, string $email, string $password): void {
        self::_create('users', [
            'name'      => "\"$name\"",
            'email'     => "\"$email\"",
            'password'  => "\"$password\""
        ]);
    }
    static public function readUser(string $condition): ?array {
        return self::_read('users', $condition);
    }
    static public function updateUser(int $uid, array $items): void {
        self::_update('users', "uid=$uid", $items);
    }
    static public function deleteUser(int $uid): void {
        if ($uid === 1)
            throw new DatabaseException('Not allowed to delete owner account');

        self::_delete('users', "uid=$uid");
    }

    // Section CRUD
    static public function createSection(string $name): void {
        $name = self::_escp($name);

        self::_create('sections', ['name' => "\"$name\""]);
    }
    static public function readSection(string $condition): ?array {
        return self::_read('sections', $condition);
    }
    static public function updateSection(int $sid, array $items): void {
        self::_update('sections', "sid=$sid", $items);
    }
    static public function deleteSection(int $sid): void {
        self::_delete('sections', "sid=$sid");
    }

    // Thread CRUD
    static public function createThread(int $sid, int $uid, string $content, ?int $head = null): void {
        $content = self::_escp($content);

        if (is_null($head))
            self::_create('threads', [
                'sid' => "$sid",
                'uid' => "$uid",
                'timestamp' => '' . time(),
                'content' => "\"$content\""
            ]);
        else 
            self::_create('threads', [
                'sid' => "$sid",
                'uid' => "$uid",
                'timestamp' => '' . time(),
                'content' => "\"$content\"",
                'head' => '' . $head
            ]);
    }
    static public function readThread(string $condition, bool $sec = false): ?array {
        $q1 = "SELECT `name`, privilege, tid, sid, users.uid, timestamp, content FROM threads, users WHERE threads.uid=users.uid AND ($condition) ORDER BY timestamp";
        $q2 = "SELECT `name`, privilege, tid, sid, users.uid, timestamp, content FROM threads, users WHERE threads.uid=users.uid AND ($condition) ORDER BY timestamp desc";

        $result = @self::$_db->query($sec ? $q2 : $q1);
        if ($result === false)
            return null;

        $rtn = [];
        while ($tmp = $result->fetch_assoc())
            array_push($rtn, $tmp);

        return $rtn;
    }
    static public function updateThread(int $tid, array $items): void {
        if (isset($items['content']))
            $items['content'] = '"' . self::_escp($items['content']) . '"';
        self::_update('threads', "tid=$tid", $items);
    }
    static public function deleteThread(int $tid): void {
        self::_delete('threads', "tid=$tid");
    }
    static public function cleanThread(): void {
        @self::$_db->query('WITH tmp AS (SELECT a.tid FROM threads a WHERE a.head IS NOT NULL AND NOT EXISTS (SELECT * FROM threads b WHERE b.tid=a.head)) DELETE FROM threads WHERE tid IN (SELECT * FROM tmp)');
    }

    // User-Section Manager CRUD
    static public function createUSM(int $sid, int $uid, string $privilege): void {
        $data = self::_escp($privilege);

        self::_create('u_s_manager', [
            'sid'       => "$sid",
            'uid'       => "$uid",
            'privilege' => "\"$privilege\""
        ]);
    }
    static public function readUSM(string $condition): ?array {
        $result = @self::$_db->query("SELECT sections.sid sid, users.uid uid, sections.name sname, users.name uname, email, u_s_manager.privilege FROM u_s_manager, sections, users WHERE u_s_manager.sid=sections.sid AND u_s_manager.uid=users.uid AND ($condition) ORDER BY sections.sid, users.uid");
        if ($result === false)
            return null;

        $rtn = [];
        while ($tmp = $result->fetch_assoc())
            array_push($rtn, $tmp);

        return $rtn;
    }
    static public function updateUSM(string $condition, array $items): void {
        self::_update('u_s_manager', $condition, $items);
    }
    static public function deleteUSM(string $condition): void {
        self::_delete('u_s_manager', $condition);
    }

    // Option CRUD
    static public function createOption(string $name, string $data): void {
        $data = self::_escp($data);

        self::_create('options', [
            'name' => "\"$name\"",
            'data' => "\"$data\""
        ]);
    }
    static public function readOption(string $name): ?string {
        $result = self::_read('options', "name=\"$name\"");
        return $result[0]['data'] ?? null;
    }
    static public function updateOption(string $name, string $data): void {
        self::_update('options', "name=\"$name\"", ['data' => "\"$data\""]);
    }
    static public function deleteOption(string $name): void {
        self::_delete('options', "name=\"$name\"");
    }

    // Table create/drop
    static private function _createTable(string $name, array $items, ?array $append = null): void {
        $ls = [];
        foreach ($items as $k => $v)
            array_push($ls, "`$k` $v");
        $ls = implode(',', $ls);

        if (is_array($append))
            foreach ($append as $k)
                $ls .= ",$k";

        if (@self::$_db->query("CREATE TABLE $name($ls)") === false)
            throw new DatabaseException('Fail to create table: ' . @self::$_db->error);
    }
    static private function _dropTable(string $name): void {
        @self::$_db->query("DROP TABLE $name");
    }

    // Basic CRUD
    static private function _create(string $table, array $items): void {
        // Concate value
        $key = implode(',', array_map(function($v) {
            return "`$v`";
        } ,array_keys($items)));
        $val = implode(',', array_values($items));

        // Query
        if (@self::$_db->query("INSERT INTO $table($key) VALUES($val)") === false)
            throw new DatabaseException('Fail to create');
    }
    static private function _read(string $table, string $condition): ?array {
        // Query
        $result = @self::$_db->query("SELECT * FROM $table WHERE $condition");
        if ($result === false)
            return null;

        // Extract data
        $rtn = [];
        while ($tmp = $result->fetch_assoc())
            array_push($rtn, $tmp);

        return $rtn;
    }
    static private function _update(string $table, string $condition, array $items): void {
        // Concate set string
        $ls = [];
        foreach ($items as $k => $v)
            array_push($ls, "`$k`=$v");
        $ls = implode(',', $ls);

        // Query
        if (@self::$_db->query("UPDATE $table SET $ls WHERE $condition") === false)
            throw new DatabaseException('Fail to update');
    }
    static private function _delete(string $table, string $condition): void {
        // Delete by condition
        @self::$_db->query("DELETE FROM $table WHERE $condition");
    }

    // Escape
    static private function _escp(string $str): string {
        return @self::$_db->real_escape_string($str);
    }

}
