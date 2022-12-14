<?php
namespace SimpleDiscuss;

// Set fake header
header("HTTP/1.1 404 Not Found");

// Generate reset token if not exists
if (!is_file("./reset_token.php")) {
    $cs = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $tk = "";
    for ($i = 0; $i < 20; ++$i)
        $tk .= $cs[rand(0, 62)];
    file_put_contents("./reset_token.php", "<?php\n\ndefine('TOKEN', '$tk');\n", LOCK_EX);
    exit;
}

// Check token
require_once("./reset_token.php");
if ($_GET['token'] === TOKEN) {
    require_once 'config.php';
    require_once 'var/Database.php';
    Database::init();
    Database::reset();
    unlink("./reset_token.php");
}
