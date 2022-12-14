<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['content']) || !isset($_POST['sid']) || !isset($_POST['tid']))
    ActionHelper::fail('Invalid POST');

if (strlen($_POST['content']) === 0)
    ActionHelper::fail('Content cannot be empty');
if (!User::isLogin())
    ActionHelper::fail('Please login');

Database::createThread(intval($_POST['sid']), intval(User::get('uid')), serialize([
    'content' => $_POST['content']
]), intval($_POST['tid']));

ActionHelper::success();
