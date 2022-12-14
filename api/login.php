<?php
namespace SimpleDiscuss;

// Check flag
if (!defined('__SIMPLE_DISCUSS__')) exit;

// Check POST params
if (!isset($_POST['email']) || !isset($_POST['email']))
    ActionHelper::fail('Invalid POST');

$username = $_POST['email'];
$password = $_POST['password'];

// Validate
if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/', $username) !== 1)
    ActionHelper::fail('Invalid email');

// Get user info
$result = Database::readUser("email=\"$username\"");
if ($result[0] === null) {
    Database::createUser($username, $username, $password);
    $result = Database::readUser("email=\"$username\"");
}

// Check password
if ($result[0]['password'] !== $password)
    ActionHelper::fail('Wrong password');

// Set login flag
User::setLogin($result[0]['uid']);

// RESTful success
ActionHelper::success();
