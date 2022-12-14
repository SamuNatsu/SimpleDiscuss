<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['sid']) || !isset($_POST['desc']))
    ActionHelper::fail('Invalid POST');
$sid = $_POST['sid'];

if (trim($_POST['desc']) === '')
    ActionHelper::fail('Description cannot be empty');
$_POST['desc'] = trim($_POST['desc']);

if (!User::isLogin())
    ActionHelper::fail('Please login');
if (User::get('privilege') < 1)
    ActionHelper::fail('Permission denied');

Database::updateSection(intval($sid), ['desc' => '"' . $_POST['desc'] . '"']);

ActionHelper::success();
