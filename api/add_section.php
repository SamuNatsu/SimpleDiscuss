<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['name']) || !isset($_POST['description']))
    ActionHelper::fail('Invalid POST');

if (strlen($_POST['name']) === 0)
    ActionHelper::fail('Name cannot be empty');
if (strlen($_POST['description']) === 0)
    ActionHelper::fail('Description cannot be empty');
if (!User::isLogin())
    ActionHelper::fail('Please login');
if (User::get('privilege') < 1)
    ActionHelper::fail('Permission denied');

Database::createSection($_POST['name']);
$section = Database::readSection("name=\"" . $_POST['name'] . "\"")[0];
Database::updateSection(intval($section['sid']), ['desc' => '"' . $_POST['description'] . '"']);

ActionHelper::success();
