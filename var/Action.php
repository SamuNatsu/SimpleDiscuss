<?php
namespace SimpleDiscuss;

// Require base class
require_once 'var/Widget.php';

class Action extends Widget {}

class ActionHelper {
    // Success quit
    static public function success(?array $msg = null): void {
        $tmp = ['status' => 'success'];
        if ($msg !== null)
            $tmp['msg'] = $msg;
        echo json_encode($tmp);
        exit;
    }

    // Fail quit
    static public function fail(?string $msg = null): void {
        $tmp = ['status' => 'fail'];
        if ($msg !== null)
            $tmp['msg'] = $msg;
        echo json_encode($tmp);
        exit;
    }

}
