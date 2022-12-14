<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['sitename']))
    ActionHelper::fail('Invalid POST');

if (strlen($_POST['sitename']) === 0)
    ActionHelper::fail('Site name cannot be empty');
if (!User::isLogin())
    ActionHelper::fail('Please login');
if (User::get('privilege') < 1)
    ActionHelper::fail('Permission denied');

Database::updateOption('site_name', $_POST['sitename']);

ActionHelper::success();
