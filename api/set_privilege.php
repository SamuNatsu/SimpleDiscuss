<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['uid']) || !isset($_POST['privilege']))
    ActionHelper::fail('Invalid POST');
$uid = intval($_POST['uid']);

if (!User::isLogin())
    ActionHelper::fail('Please login');
if (User::get('privilege') < 1)
    ActionHelper::fail('Permission denied');

if ($_POST['privilege'] !== '0' && $_POST['privilege'] !== '1')
    ActionHelper::fail('Invalid privilege');

$usm = Database::readUser("uid=$uid");
if ($usm === null || count($usm) === 0)
    ActionHelper::fail('User not exists');

Database::updateUser(intval($uid), ['privilege'=>$_POST['privilege']]);

ActionHelper::success();
