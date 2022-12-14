<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['uid']) || !isset($_POST['name']))
    ActionHelper::fail('Invalid POST');
$uid = $_POST['uid'];

if (trim($_POST['name']) === '')
    ActionHelper::fail('Name cannot be empty');
$_POST['name'] = trim($_POST['name']);

if (!User::isLogin())
    ActionHelper::fail('Please login');

Database::updateUser(intval($uid), ['name' => '"' . $_POST['name'] . '"']);

ActionHelper::success();
