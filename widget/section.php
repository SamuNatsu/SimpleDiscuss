<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

$section = Database::readSection('sid=' . Router::$matches[1])[0];

$title = Database::readOption('site_name');

Section::fetchAll();

Widget::get('header')->title = $section['name'] . ' | ' . $title;
Widget::get('header')->sitename = $title;
Widget::get('header')->render();

Widget::get('footer')->sitename = $title;

Thread::fetchAll($section['sid']);
?>

<div class="flex-row flex-m-evenly forum-main">
    <div class="flex-col forum-section">
        <div class="forum-block">
            <h2><?php echo $section['name'] ?></h2>
            <p><?php echo $section['desc'] ?></p>
            <?php if (User::isLogin()): ?>
            <hr class="forum-section-hr"/>
            <h2>Start new thread</h2>
            <input id="new-thread-title" class="login-input" type="text" placeholder="Title"/>
            <textarea id="new-thread-content" class="login-input" placeholder="Content"></textarea>
            <div id="new-thread-submit" class="btn" style="width:fit-content" data-sid="<?php echo $section['sid'] ?>">Start</div>
            <?php endif; ?>
        </div>
        <?php Thread::begin() ?>
        <?php while (Thread::next()): ?>
        <div class="flex-col forum-block">
            <?php $th = unserialize(Thread::get('content')); ?>
            <div class="forum-thread-title animation-hover-underline" data-tid="<?php echo Thread::get('tid'); ?>"><?php echo $th['title'] ?></div>
            <hr class="forum-section-hr"/>
            <div class="forum-section-desc"><pre><?php echo $th['content']; ?></pre/></div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php Widget::get('sidebar')->render(); ?>
</div>

<?php Widget::get('footer')->render() ?>