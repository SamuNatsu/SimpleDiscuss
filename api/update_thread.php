<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['tid']) || !isset($_POST['content']))
    ActionHelper::fail('Invalid POST');
$tid = $_POST['tid'];

if (trim($_POST['content']) === '')
    ActionHelper::fail('Content cannot be empty');
$_POST['content'] = trim($_POST['content']);

if (!User::isLogin())
    ActionHelper::fail('Please login');

$thread = Database::readThread("tid=$tid")[0];
$moderator = Database::readUSM("users.uid=" . User::get('uid') . " AND sections.sid=" . $thread['sid']);
if (User::get('uid') !== $thread['uid'] && User::get('privilege') < 1 && count($moderator) === 0)
    ActionHelper::fail('Permission denied');

$content = unserialize($thread['content']);
$content['content'] = $_POST['content'];

Database::updateThread(intval($tid), ['content' => serialize($content)]);

ActionHelper::success();
