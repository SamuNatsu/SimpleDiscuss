<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['sid']))
    ActionHelper::fail('Invalid POST');
$sid = $_POST['sid'];

if (!User::isLogin())
    ActionHelper::fail('Please login');
if (User::get('privilege') < 1)
    ActionHelper::fail('Permission denied');

Database::deleteSection($sid);

ActionHelper::success();
