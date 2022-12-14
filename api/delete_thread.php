<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['tid']))
    ActionHelper::fail('Invalid POST');
$tid = $_POST['tid'];

if (!User::isLogin())
    ActionHelper::fail('Please login');

$thread = Database::readThread("tid=$tid")[0];
$moderator = Database::readUSM("users.uid=" . User::get('uid') . " AND sections.sid=" . $thread['sid']);
if ($thread['uid'] !== User::get('uid') && User::get('privilege') < 1 && count($moderator) === 0) 
    ActionHelper::fail('Permission denied');

Database::deleteThread($tid);

ActionHelper::success();
