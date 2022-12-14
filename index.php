<?php
namespace SimpleDiscuss;

ini_set('date.timezone', 'Asia/Shanghai');

// Start up flag
define('__SIMPLE_DISCUSS__', '');

// Require config
if (!is_file('config.php')) {
	header('HTTP/1.1 500 Internal Server Error');
	exit;
}
require_once 'config.php';

// Require components
require_once 'var/Action.php';
require_once 'var/Database.php';
require_once 'var/Moderator.php';
require_once 'var/Path.php';
require_once 'var/Router.php';
require_once 'var/Section.php';
require_once 'var/Thread.php';
require_once 'var/User.php';
require_once 'var/Widget.php';

// Initialize paths
Path::$rewrite = true;
Path::register('root', './');
Path::register('api', './api');
Path::register('css', './css');
Path::register('js', './js');
Path::register('widget', './widget');

// Auto register widgets
Widget::autoRegister(Path::dir('widget'));

// Initialize router
Router::register('@^/api/([\w]+)(/\S*)?$@', 'action');
Router::register('@^/admin$@', 'admin');
Router::register('@^$@', 'index');
Router::register('@^/section/([1-9][\d]*)(/\S*)?$@', 'section');
Router::register('@^/thread/([1-9][\d]*)(/\S*)?$@', 'thread');
Router::register('@^/user/([1-9][\d]*)(/\S*)?$@', 'user');

// Initialize database
Database::init();

// Initialize user system
User::init();

// Despatch router
Router::despatch();
