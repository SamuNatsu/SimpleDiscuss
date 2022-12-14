<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $this->title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="<?php Path::_url('css', '/modern-normalize.min.css'); ?>"/>
    <link rel="stylesheet" href="<?php Path::_url('css', '/flex.css'); ?>"/>
    <link rel="stylesheet" href="<?php Path::_url('css', '/style.css'); ?>"/>
    <link rel="stylesheet" href="<?php Path::_url('css', '/animation.css'); ?>"/>
    <script src="<?php Path::_url('js', '/jquery.min.js'); ?>"></script>
    <script src="<?php Path::_url('js', '/hashes.min.js'); ?>"></script>
</head>
<body class="flex-col">
    <script>let forumHost = "<?php Path::_vurl(''); ?>"; </script>
    <header class="flex-col forum-header">
        <div class="flex-row forum-title"><?php echo $this->sitename; ?></div>
        <nav class="flex-row forum-nav">
            <?php Section::begin() ?>
            <?php while (Section::next()): ?>
            <div title="<?php echo Section::get('name'); ?>" data-sid="<?php echo Section::get('sid'); ?>"><?php echo Section::get('name'); ?></div>
            <?php endwhile; ?>
        </nav>
    </header>
