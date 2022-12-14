<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['uid']) || !isset($_POST['password']))
    ActionHelper::fail('Invalid POST');
$uid = $_POST['uid'];

if (!User::isLogin())
    ActionHelper::fail('Please login');

Database::updateUser(intval($uid), ['password' => '"' . $_POST['password'] . '"']);

ActionHelper::success();
