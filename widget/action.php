<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

Action::autoRegister(Path::dir('api'), 'api_');

Action::get('api_' . Router::$matches[1])->render();
