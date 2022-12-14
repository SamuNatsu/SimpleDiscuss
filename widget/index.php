<?php
namespace SimpleDiscuss;

if (!defined('__SIMPLE_DISCUSS__')) exit;

Section::fetchAll();

$title = Database::readOption('site_name');
$header = unserialize(Database::readOption('header_block'));

Widget::get('header')->title = $title;
Widget::get('header')->sitename = $title;

Widget::get('footer')->sitename = $title;

Widget::get('header')->render();
?>

<div class="flex-row flex-m-evenly forum-main">
    <div class="flex-col forum-section">
        <?php if (User::get('privilege') == 2): ?>
        <div class="forum-block">
            <h2>Add new section</h2>
            <input id="new-section-title" class="login-input" type="text" placeholder="Name"/>
            <textarea id="new-section-desc" class="login-input" placeholder="Description"></textarea>
            <div id="new-section-submit" class="btn" style="width:fit-content" data-sid="<?php echo $section['sid'] ?>">Start</div>
        </div>
        <?php endif; ?>
        <?php Section::begin() ?>
        <?php while (Section::next()): ?>
        <div class="flex-col forum-block">
            <div class="forum-section-title animation-hover-underline" data-sid="<?php echo Section::get('sid'); ?>"><?php echo Section::get('name'); ?></div>
            <hr class="forum-section-hr"/>
            <div class="forum-section-desc"><pre><?php echo Section::get('desc'); ?></pre></div>
            <?php if (User::get('privilege') == 2): ?>
            <div class="thread-tag"><br/>
                <a href="#" class="section-delete-btn op-btn" data-sid="<?php echo Section::get('sid')?>">Delete</a> | <a href="#" class="section-rename-btn op-btn" data-sid="<?php echo Section::get('sid')?>">Update name</a> | <a href="#" class="section-redesc-btn op-btn" data-sid="<?php echo Section::get('sid')?>">Update description</a>
            </div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
    <?php Widget::get('sidebar')->render(); ?>
</div>

<?php Widget::get('footer')->render(); ?>
