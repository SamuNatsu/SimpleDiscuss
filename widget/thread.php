<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

$thread = Database::readThread('tid=' . Router::$matches[1])[0];
$content = unserialize($thread['content']);

$title = Database::readOption('site_name');

Section::fetchAll();

Widget::get('header')->title = $content['title'] . ' | ' . $title;
Widget::get('header')->sitename = $title;
Widget::get('header')->render();

Widget::get('footer')->sitename = $title;

Thread::fetch($thread['tid']);
Moderator::fetchAll();
?>

<div class="flex-row flex-m-evenly forum-main">
    <div class="flex-col forum-section">
        <div class="forum-block">
            <h2>Discuss on "<?php echo $content['title'] ?>"</h2>
            <?php if (User::isLogin()): ?>
            <hr class="forum-section-hr"/>
            <h2>Reply</h2>
            <textarea id="reply-content" class="login-input" placeholder="Content"></textarea>
            <div id="reply-submit" class="btn" style="width:fit-content" data-sid="<?php echo $thread['sid'] ?>" data-tid="<?php echo $thread['tid']?>">Reply</div>
            <?php endif; ?>
        </div>
        <?php Thread::begin() ?>
        <?php $cnt = 1; ?>
        <?php while (Thread::next()): ?>
        <div class="flex-col forum-block">
            <?php $th = unserialize(Thread::get('content')); ?>
            <div class="forum-section-desc">
                <div><?php echo $th['content']; ?></div>
                <hr class="forum-thread-hr"/>
                <div class="thread-tag"><?php echo $cnt ?># | <?php echo Thread::get('name') ?>(<?php echo Thread::get('uid') ?>) @<?php echo date("Y/m/d H:i:s", Thread::get('timestamp')) ?>
                <?php if (Thread::get('uid') === User::get('uid') || User::get('privilege') > 0 || Moderator::check(Thread::get('sid'), User::get('uid'))): ?>
                 | <a href="#" class="thread-delete-btn" data-tid="<?php echo Thread::get('tid')?>" data-cnt="<?php echo $cnt ?>">Delete</a> | <a href="#" class="thread-update-btn op-btn" data-tid="<?php echo Thread::get('tid')?>" data-cnt="<?php echo $cnt ?>">Update</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
        <?php ++$cnt; ?>
        <?php endwhile; ?>
    </div>
    <?php Widget::get('sidebar')->render(); ?>
</div>

<?php Widget::get('footer')->render() ?>
