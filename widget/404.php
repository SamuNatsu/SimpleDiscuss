<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

header('HTTP/1.1 404 Not Found');

?>
<!DOCTYPE html>
<html>
<head>
    <title>404 Oops!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="<?php Path::_url('css', '/modern-normalize.min.css'); ?>"/>
    <link rel="stylesheet" href="<?php Path::_url('css', '/flex.css'); ?>"/>
    <style>
        body {color:#bbb;font-weight:bold;height:100%;position:fixed;width:100%}
        .text-1 {font-size:7em}
        .text-2 {font-size:2em}
    </style>
</head>
<body class="flex-row flex-m-center flex-c-center">
    <div class="flex-row flex-c-end">
        <div class="text-1">404</div>
        <div class="flex-col text-2">
            <div>Not</div>
            <div>Found</div>
        </div>
    </div>
</body>
</html>
