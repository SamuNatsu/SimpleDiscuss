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

$usm = Database::readUSM("sections.sid=$sid AND users.uid=$uid");
if ($usm === null || count($usm) === 0)
    ActionHelper::fail('Moderator not exists');

Database::deleteUSM("sid=$sid AND uid=$uid");

ActionHelper::success();
