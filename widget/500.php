<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

header('HTTP/1.1 500 Internal Server Error');

function printError(\Throwable $err) {
    while ($err->getPrevious() !== null)
        $err = $err->getPrevious();
    echo 'Message: ' . $err->getMessage() . PHP_EOL;
    echo 'Code: ' . $err->getCode() . PHP_EOL;
    $tmp = basename($err->getFile());
    echo "Location: $tmp({$err->getLine()})" . PHP_EOL;
    echo 'Trace:' . PHP_EOL;
    $cnt = 0;
    foreach ($err->getTrace() as $item) {
        $tmp = basename($item['file']);
        echo "  #$cnt: $tmp({$item['line']}) => {$item['class']}{$item['type']}{$item['function']}".PHP_EOL;
        ++$cnt;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>500 Oops!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="<?php Path::_url('css', '/modern-normalize.min.css'); ?>"/>
    <link rel="stylesheet" href="<?php Path::_url('css', '/flex.css'); ?>"/>
    <style>
        body {height:100%;position:fixed;width:100%}
        .box-1 {background:#eee;border-radius:20px;margin-top:20px;padding:30px}
        .text-1 {color:#777;font-size:3em;font-weight:bold}
        .text-2 {margin:0;white-space:pre-wrap;word-break:break-all}
    </style>
</head>
<body class="flex-row flex-m-center flex-c-center">
    <div class="flex-col flex-c-center" style="max-width:90%">
        <div class="text-1">Error</div>
        <div class="box-1"><pre class="text-2"><?php printError($this->err); ?></pre></div>
    </div>
</body>
</html>
