<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['sid']) || !isset($_POST['uid']))
    ActionHelper::fail('Invalid POST');
$sid = intval($_POST['sid']);
$uid = intval($_POST['uid']);

if (!User::isLogin())
    ActionHelper::fail('Please login');
if (User::get('privilege') < 1)
    ActionHelper::fail('Permission denied');

$user = Database::readUser("uid=$uid");
if ($user === null || count($user) === 0)
    ActionHelper::fail('User not found');
$section = Database::readSection("sid=$sid");
if ($section === null || count($section) === 0)
    ActionHelper::fail('Section not found');
$usm = Database::readUSM("sections.sid=$sid AND users.uid=$uid");
if (count($usm) !== 0)
    ActionHelper::fail('Moderator already exists');

Database::createUSM($sid, $uid, "1");

ActionHelper::success();
