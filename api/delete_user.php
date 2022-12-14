<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['uid']))
    ActionHelper::fail('Invalid POST');
$uid = intval($_POST['uid']);

if (!User::isLogin())
    ActionHelper::fail('Please login');
if (User::get('privilege') < 1 || $uid < 2)
    ActionHelper::fail('Permission denied');

$usm = Database::readUser("uid=$uid");
if ($usm === null || count($usm) === 0)
    ActionHelper::fail('User not exists');

Database::deleteUser(intval($uid));

ActionHelper::success();
