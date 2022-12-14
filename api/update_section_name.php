<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['sid']) || !isset($_POST['name']))
    ActionHelper::fail('Invalid POST');
$sid = $_POST['sid'];

if (trim($_POST['name']) === '')
    ActionHelper::fail('Name cannot be empty');
$_POST['name'] = trim($_POST['name']);

if (!User::isLogin())
    ActionHelper::fail('Please login');
if (User::get('privilege') < 1)
    ActionHelper::fail('Permission denied');

Database::updateSection(intval($sid), ['name' => '"' . $_POST['name'] . '"']);

ActionHelper::success();
