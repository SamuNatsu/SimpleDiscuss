<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

if (!isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['sid']))
    ActionHelper::fail('Invalid POST');

if (strlen($_POST['title']) === 0)
    ActionHelper::fail('Title cannot be empty');
if (strlen($_POST['content']) === 0)
    ActionHelper::fail('Content cannot be empty');
if (!User::isLogin())
    ActionHelper::fail('Please login');

Database::createThread(intval($_POST['sid']), intval(User::get('uid')), serialize([
    'title' => $_POST['title'], 
    'content' => $_POST['content']
]));

ActionHelper::success();
